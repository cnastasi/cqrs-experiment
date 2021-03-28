<?php

use Behat\Behat\Context\Context;
use CQRS\Aggregate\Counter;
use CQRS\Command\Add;
use CQRS\Command\CommandBus;
use CQRS\Command\CounterCommandHandler;
use CQRS\Command\Subtract;
use CQRS\Event\EventStore;
use CQRS\State\CounterState;
use CQRS\Event\EventBus;
use function PHPUnit\Framework\assertSame;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private Counter $counter;
    private CounterState $counterState;
    private EventBus $eventBus;
    private CommandBus $commandBus;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given the counter set to :value
     */
    public function theCounterSetTo(int $value): void
    {
        $this->eventBus = new EventBus();
        $this->commandBus = new CommandBus();
        $this->eventStore = new EventStore($this->eventBus);

        $this->counterState = new CounterState($value);
        $this->counter = new Counter($this->counterState, $this->eventBus);

        $this->commandBus->registerHandler(new CounterCommandHandler($this->counter));
    }

    /**
     * @When /^plus button is pressed$/
     */
    public function plusButtonIsPressed(): void
    {
        $this->commandBus->dispatch(new Add(1));
    }

    /**
     * @Then the counter value is :value
     */
    public function theCounterValueIs(int $value): void
    {
        assertSame($value, $this->counterState->value());
    }

    /**
     * @When /^minus button is pressed$/
     */
    public function minusButtonIsPressed()
    {
        $this->commandBus->dispatch(new Subtract(1));
    }
}
