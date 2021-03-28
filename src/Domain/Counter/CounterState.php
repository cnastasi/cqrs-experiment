<?php

declare(strict_types=1);

namespace CQRS\Domain\Counter;

use CQRS\Domain\Counter\Event\Added;
use CQRS\Domain\Counter\Event\CounterInitialized;
use CQRS\Domain\Counter\Event\LowerLimitReached;
use CQRS\Domain\Counter\Event\Subtracted;
use CQRS\Infrastructure\Event\EventStore;
use LogicException;

final class CounterState
{
    private int $value = 0;

    private int $limitReached = 0;


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
        }
        if ($event instanceof Added) {
            $this->value += $event->value();
        } else if ($event instanceof LowerLimitReached) {
            $this->limitReached++;
        } else if ($event instanceof Subtracted) {
            $this->value -= $event->value();

            if ($this->value < 0) {
                throw new LogicException('Counter must not be negative');
            }
        }
    }

    public function value(): int
    {
        return $this->value;
    }

    public function hasWon()
    {
        return $this->limitReached >= 5;
    }
}