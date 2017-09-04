<?php

namespace Daikon\BotMan;

interface BotInterface
{
    public function run(array $parameters = []): void;
}
