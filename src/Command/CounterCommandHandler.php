<?php

declare(strict_types=1);

namespace CQRS\Command;

use CQRS\Aggregate\Counter;

class CounterCommandHandler implements CommandHandler
{
    private Counter $counter;

    public function __construct(Counter $counter)
    {
        $this->counter = $counter;
    }

    public function handle(object $command): void
    {
        if ($command instanceof CounterCommand) {
            switch ($command::class) {
                case Add::class:
                    $this->counter->plus($command->value());
                    break;

                case Subtract::class:
                    $this->counter->minus($command->value());
                    break;
            }
        }
    }
}