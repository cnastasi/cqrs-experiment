<?php

declare(strict_types=1);

namespace CQRS;

class EventBus
{
    /** @var EventListener[] */
    private array $listeners = [];

    public function addListener(EventListener $listener): void
    {
        $this->listeners[] = $listener;
    }

    public function dispatch(object $event): void
    {
        foreach ($this->listeners as $listener) {
            $listener->apply($event);
        }
    }
}