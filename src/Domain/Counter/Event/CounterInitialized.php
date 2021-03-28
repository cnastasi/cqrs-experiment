<?php

declare(strict_types=1);

namespace CQRS\Domain\Counter\Event;

class CounterInitialized
{
    private int $initValue;

    public function __construct(int $initValue)
    {
        $this->initValue = $initValue;
    }

    public function initValue(): int
    {
        return $this->initValue;
    }

}