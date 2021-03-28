<?php

declare(strict_types=1);

namespace CQRS\Domain\Counter\Command;

final class Add implements CounterCommand
{
    private int $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function value(): int
    {
        return $this->value;
    }
}