<?php

use Behat\Behat\Context\Context;
use CQRS\Aggregate\Counter;
use CQRS\Command\Add;
use CQRS\Command\CommandBus;
use CQRS\Command\CounterCommandHandler;
use CQRS\Command\Subtract;
use CQRS\Event\CounterInitialized;
use CQRS\Event\EventStore;
use CQRS\State\CounterState;
use CQRS\Event\EventBus;
use function PHPUnit\Framework\assertSame;
use function PHPUnit\Framework\assertTrue;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private EventBus $eventBus;
    private CommandBus $commandBus;
    private EventStore $eventStore;

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
        $this->eventStore = new EventStore();
        $this->eventStore->apply(new CounterInitialized($value));

        $this->eventBus = new EventBus();
        $this->eventBus->addListener($this->eventStore);

        $this->commandBus = new CommandBus();
        $this->commandBus->registerHandler(new CounterCommandHandler($this->eventStore, $this->eventBus));
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

        $counter = Counter::create($this->eventStore, $this->eventBus);

        assertSame($value, $counter->value());
    }

    /**
     * @When /^minus button is pressed$/
     */
    public function minusButtonIsPressed()
    {
        $this->commandBus->dispatch(new Subtract(1));
    }

    /**
     * @Given /^he win a puppy$/
     */
    public function heWinAPuppy()
    {
        $counter = Counter::create($this->eventStore, $this->eventBus);

        assertTrue($counter->hasWon());

    }
}
