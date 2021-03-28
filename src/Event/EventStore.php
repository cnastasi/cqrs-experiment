<?php

declare(strict_types=1);

namespace CQRS\Event;

class EventStore implements EventListener
{
    private array $stream = [];

    public function __construct(EventBus $eventBus)
    {
        $eventBus->addListener($this);
    }

    public function apply(object $event): void
    {
        $this->stream[] = $event;
    }
}