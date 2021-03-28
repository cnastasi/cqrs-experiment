<?php

declare(strict_types=1);

namespace CQRS\Infrastructure\Command;

interface CommandHandler
{
    public function handle(object $command): void;
}