<?php

declare(strict_types=1);

namespace CQRS\Command;

interface CommandHandler
{
    public function handle(object $command): void;
}