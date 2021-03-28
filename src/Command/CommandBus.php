<?php

declare(strict_types=1);

namespace CQRS\Command;

class CommandBus
{
    /** @var CommandHandler[] */
    private array $handlers = [];

    public function registerHandler(CommandHandler $handler): void
    {
        $this->handlers[] = $handler;
    }

    public function dispatch(object $command): void
    {
        foreach ($this->handlers as $handler) {
            $handler->handle($command);
        }
    }
}