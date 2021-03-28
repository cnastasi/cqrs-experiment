<?php

declare(strict_types=1);

namespace CQRS;

interface EventListener
{
    public function apply(object $event): void;
}