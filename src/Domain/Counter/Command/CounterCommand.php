<?php

declare(strict_types=1);

namespace CQRS\Domain\Counter\Command;

interface CounterCommand
{
    public function value(): int;
}