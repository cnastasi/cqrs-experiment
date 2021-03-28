<?php

use Behat\Behat\Context\Context;
use CQRS\Domain\Counter\Command\Add;
use CQRS\Domain\Counter\Command\CounterCommandHandler;
use CQRS\Domain\Counter\Command\Subtract;
use CQRS\Domain\Counter\Counter;
use CQRS\Domain\Counter\Event\CounterInitialized;
use CQRS\Infrastructure\Command\CommandBus;
use CQRS\Infrastructure\Event\EventBus;
use CQRS\Infrastructure\Event\EventStore;
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
     * @When plus button is pressed
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
     * @When minus button is pressed
     */
    public function minusButtonIsPressed()
    {
        $this->commandBus->dispatch(new Subtract(1));
    }

    /**
     * @Given he win a puppy
     */
    public function heWinAPuppy()
    {
        $counter = Counter::create($this->eventStore, $this->eventBus);

        assertTrue($counter->hasWon());

    }
}
