<?php

namespace Daikon\BotMan\Bot;

use BotMan\BotMan\BotMan;
use BotMan\Drivers\Slack\Factory as BotManFactory;
use React\EventLoop\Factory;
use Daikon\BotMan\BotInterface;
use Dailex\Bot\BotaFett\Bounty\Register;
use Dailex\Service\ServiceLocatorInterface;

final class SlackRTMBot implements BotInterface
{
    private $serviceLocator;

    private $settings;

    public function __construct(ServiceLocatorInterface $serviceLocator, array $settings)
    {
        $this->serviceLocator = $serviceLocator;
        $this->settings = $settings;
    }

    public function run(array $parameters = []): void
    {
        $loop = Factory::create();
        $botman = (new BotManFactory)->createForRTM([
            'slack' => [
                'token' => $this->settings['slack_token']
            ]
        ], $loop);

        $this->registerHears($botman);
        $this->registerFallback($botman);

        $loop->run();
    }

    private function registerHears(BotMan $botman): void
    {
        foreach ($this->settings['hears'] ?? [] as $hearing) {
            $botman->hears($hearing['pattern'], function(BotMan $bot) use ($hearing) {
                $this->serviceLocator->make($hearing['handler'])->handle($bot, $this->settings);
            }, $hearing['in'] ?? null);
        }
    }

    private function registerFallback(BotMan $botman)
    {
        if (isset($this->settings['fallback'])) {
            $botman->fallback(function(BotMan $bot) use ($fallback) {
                $this->serviceLocator->make($fallback)->handle($bot, $this->settings);
            });
        }
    }
}
