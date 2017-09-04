<?php

namespace Daikon\BotMan;

use Daikon\DataStructure\TypedMapTrait;

final class BotMap implements \IteratorAggregate, \Countable
{
    use TypedMapTrait;

    public function __construct(array $workers = [])
    {
        $this->init($workers, BotInterface::class);
    }
}
