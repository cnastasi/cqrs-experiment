<?php

declare(strict_types=1);

namespace CQRS\Domain\Counter\Command;

use CQRS\Domain\Counter\Counter;
use CQRS\Infrastructure\Command\CommandHandler;
use CQRS\Infrastructure\Event\EventBus;
use CQRS\Infrastructure\Event\EventStore;

class CounterCommandHandler implements CommandHandler
{
    private Counter $counter;
    private EventStore $eventStore;
    private EventBus $eventBus;

    public function __construct(EventStore $eventStore, EventBus $eventBus)
    {
        $this->eventStore = $eventStore;
        $this->eventBus = $eventBus;
    }

    public function handle(object $command): void
    {
        if ($command instanceof CounterCommand) {
            $this->counter = Counter::create($this->eventStore, $this->eventBus);
            $this->routeCommand($command);
        }
    }

    private function routeCommand(CounterCommand $command): void
    {
        switch ($command::class) {
            case Add::class:
                $this->counter->plus($command->value());
                break;

            case Subtract::class:
                $this->counter->minus($command->value());
                break;
        }
    }
}