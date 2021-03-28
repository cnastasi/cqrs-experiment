<?php

declare(strict_types=1);

namespace CQRS\Aggregate;


use Closure;
use CQRS\Event\Added;
use CQRS\Event\EventStore;
use CQRS\Event\LowerLimitReached;
use CQRS\Event\Subtracted;
use CQRS\State\CounterState;
use CQRS\Event\EventBus;

class Counter
{
    private CounterState $state;
    private Closure $eventBus;

    private function __construct(CounterState $state, Closure $eventBus)
    {
        $this->state = $state;

        $this->eventBus = $eventBus;
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

    public static function create(EventStore $eventStore, EventBus $eventBus) {
        $state = new CounterState($eventStore);

        return new Counter($state, function (object $event) use ($state, $eventBus) {
            $state->apply($event);
            $eventBus->dispatch($event);
        });
    }

    public function value(): int {
        return $this->state->value();
    }

    public function hasWon(): bool
    {
        return $this->state->hasWon();
    }
}