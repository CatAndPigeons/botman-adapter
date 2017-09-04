<?php

namespace Daikon\BotMan;

use BotMan\BotMan\BotMan;

interface BotHandlerInterface
{
    public function handle(BotMan $bot, array $settings = []): void;
}
