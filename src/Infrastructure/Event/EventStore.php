<?php

declare(strict_types=1);

namespace CQRS\Infrastructure\Event;

class EventStore implements EventListener
{
    private array $stream = [];

    public function apply(object $event): void
    {
        $this->stream[] = $event;
    }

    public function stream():array {
        return $this->stream;
    }
}