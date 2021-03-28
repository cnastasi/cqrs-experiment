<?php

declare(strict_types=1);

namespace CQRS\Event;

interface EventListener
{
    public function apply(object $event): void;
}