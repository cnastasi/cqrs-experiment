<?php

declare(strict_types=1);

namespace CQRS\Aggregate;


use CQRS\Command\Add;
use CQRS\State\CounterState;
use CQRS\Event\EventBus;
use CQRS\Event\EventListener;
use CQRS\Command\Subtract;

class Counter implements EventListener
{
    private CounterState $state;
    private EventBus $eventBus;

    public function __construct(CounterState $state, EventBus $eventBus)
    {
        $this->state = $state;
        $this->eventBus = $eventBus;

        $this->eventBus->addListener($this);
    }

    public function plus()
    {
        $this->eventBus->dispatch(new Add(1));
    }

    public function minus()
    {
        $this->eventBus->dispatch(new Subtract(1));
    }

    public function apply(object $event): void {
        if ($event instanceof Add) {
            $this->state->addBy($event->value());
        }
        else if ($event instanceof Subtract) {
            $this->state->subtractBy($event->value());

        }
    }
}