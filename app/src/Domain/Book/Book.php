<?php


namespace App\Domain\Book;

use App\Domain\Book\Event\BookDescriptionWasChanged;
use App\Domain\Book\Event\BookNameWasChanged;
use App\Domain\Book\Event\BookWasCreated;
use App\Domain\Book\ValueObject\Description;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class Book extends AggregateRoot
{
    /**
     * @var AggregateRootId
     */
    protected $id;

    /**
     * @var Name
     */
    protected $name;

    /**
     * @var Description
     */
    protected $description;

    public static function create(AggregateRootId $id, Name $name, ValueObject\Description $description): self
    {
        $self = new self();
        $self->recordThat(BookWasCreated::createWithData($id, $name, $description));

        return $self;
    }

    function applyBookWasCreated(BookWasCreated $bookWasCreated)
    {
        $this->id = $bookWasCreated->getId();
        $this->name = $bookWasCreated->getName();
        $this->description = $bookWasCreated->getDescription();
    }

    public function changeName(string $name)
    {
        $this->name->changeName($name);
        $this->recordThat(BookNameWasChanged::createWithData($this->id, $this->name));
    }

    public function changeDescription(string $description)
    {
        $this->description->changeDescription($description);
        $this->recordThat(BookDescriptionWasChanged::createWithData($this->id, $this->description));
    }

    protected function applyBookDescriptionWasChanged(BookDescriptionWasChanged $bookDescriptionWasChanged)
    {
        $this->description = $bookDescriptionWasChanged->getDescription();
    }

    protected function applyBookNameWasChanged(BookNameWasChanged $bookNameWasChanged)
    {
        $this->name = $bookNameWasChanged->getName();
    }

    protected function aggregateId(): string
    {
        return $this->id->toString();
    }

    /**
     * Apply given event.
     */
    protected function apply(AggregateChanged $e): void
    {
        $handler = $this->determineEventHandlerMethodFor($e);
        if (!\method_exists($this, $handler)) {
            throw new \RuntimeException(\sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                \get_class($this)
            ));
        }
        $this->{$handler}($e);
    }

    protected function determineEventHandlerMethodFor(AggregateChanged $e): string
    {
        return 'apply' . \implode(\array_slice(\explode('\\', \get_class($e)), -1));
    }
}