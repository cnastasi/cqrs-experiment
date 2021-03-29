<?php

declare(strict_types=1);

namespace CQRS\Domain\Counter\Query;

use CQRS\Domain\Counter\Event\Added;
use CQRS\Infrastructure\Event\EventListener;

class QueryRunner
{
    public function run(object $q): array
    {
        if ($q instanceof GetCounter) {

        }
    }
}