<?php

declare(strict_types=1);

use CQRS\Domain\Counter\Event\Added;
use CQRS\Domain\Counter\Event\CounterInitialized;
use CQRS\Domain\Counter\Event\Subtracted;
use CQRS\Infrastructure\Event\EventStore;

class CounterReadModel
{
    private int $value;
    private int $addCount = 0;
    private $subtractCount = 0;

    public function __construct(EventStore $eventStore)
    {
        foreach ($eventStore->stream() as $event) {
            $this->apply($event);
        }
    }

    public function apply(object $event): void
    {
        if ($event instanceof CounterInitialized) {
            $this->value = $event->initValue();
        } else if ($event instanceof Added) {
            $this->value += $event->value();
            $this->addCount++;
        } else if ($event instanceof Subtracted) {
            $this->value -= $event->value();
            $this->subtractCount ++;
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function addCount(): int {
        return $this->addCount;
    }

    public function subtractCount(): int
    {
        return $this->subtractCount;
    }

}