<?php

declare(strict_types=1);

namespace CQRS\Command;

interface CounterCommand
{
    public function value(): int;
}