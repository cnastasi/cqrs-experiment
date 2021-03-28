<?php

declare(strict_types=1);

namespace CQRS\State;

final class CounterState
{
    private int $value;

    public function __construct(int $initialValue = 0)
    {
        $this->value = $initialValue;
    }

    public function addBy(int $value): void
    {
        $this->value += $value;
    }

    public function value(): int
    {
        return $this->value;
    }

    public function subtractBy(int $value)
    {
        $this->value = max($this->value - $value, 0);
    }


}