<?php

declare(strict_types=1);

namespace CQRS\Domain\Counter\Event;

class Subtracted
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