<?php

declare(strict_types=1);

namespace CQRS\Domain\Counter;


use Closure;
use CQRS\Domain\Counter\Event\Added;
use CQRS\Domain\Counter\Event\LowerLimitReached;
use CQRS\Domain\Counter\Event\Subtracted;
use CQRS\Infrastructure\Event\EventBus;
use CQRS\Infrastructure\Event\EventStore;

class Counter
{
    private CounterState $state;
    private Closure $eventBus;

    private function __construct(CounterState $state, Closure $eventBus)
    {
        $this->state = $state;

        $this->eventBus = $eventBus;
    }

    public static function create(EventStore $eventStore, EventBus $eventBus)
    {
        $state = new CounterState($eventStore);

        return new Counter($state, function (object $event) use ($state, $eventBus) {
            $state->apply($event);
            $eventBus->dispatch($event);
        });
    }

    public function plus(int $value): void
    {
        ($this->eventBus)(new Added($value));
    }

    public function minus(int $value): void
    {
        if ($this->state->value() === 0) {
            ($this->eventBus)(new LowerLimitReached());
        } else {
            ($this->eventBus)(new Subtracted($value));
        }
    }

    public function value(): int
    {
        return $this->state->value();
    }

    public function hasWon(): bool
    {
        return $this->state->hasWon();
    }
}