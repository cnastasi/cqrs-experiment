<?php

declare(strict_types=1);

namespace CQRS\Command;

use CQRS\Aggregate\Counter;
use CQRS\Event\EventBus;
use CQRS\Event\EventStore;
use CQRS\State\CounterState;

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