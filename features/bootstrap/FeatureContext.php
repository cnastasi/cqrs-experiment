<?php

use Behat\Behat\Context\Context;
use CQRS\Aggregate\Counter;
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
        $this->counterState = new CounterState($value);
        $this->counter = new Counter($this->counterState, $this->eventBus);
    }

    /**
     * @When /^plus button is pressed$/
     */
    public function plusButtonIsPressed(): void
    {
        $this->counter->plus();
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
        $this->counter->minus();
    }
}
