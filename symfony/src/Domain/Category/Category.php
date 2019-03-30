<?php

declare(strict_types=1);

namespace App\Domain\Category;

use App\Domain\Category\Events\CategoryNameWasChanged;
use App\Domain\Category\Events\CategoryWasCreated;
use App\Domain\Category\Events\CategoryWasDeleted;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

class Category extends AggregateRoot
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * @var Name
     */
    private $name;

    public static function create(AggregateRootId $generate, Name $name): Category
    {
        $self = new self();
        $self->recordThat(CategoryWasCreated::createWithData($generate, $name));

        return $self;
    }

    public function changeName(string $name)
    {
        $this->recordThat(CategoryNameWasChanged::createWithData($this->getId(), $this->name->changeName($name)));
    }

    public function applyCategoryNameWasChanged(CategoryNameWasChanged $categoryNameWasChanged)
    {
        $this->name = $categoryNameWasChanged->getName();
    }

    public function delete()
    {
        $this->recordThat(CategoryWasDeleted::createWithData($this->getId()));
    }

    protected function applyCategoryWasDeleted(CategoryWasDeleted $categoryWasDeleted)
    {
    }

    protected function applyCategoryWasCreated(CategoryWasCreated $categoryWasCreated): void
    {
        $this->id = $categoryWasCreated->getId();
        $this->name = $categoryWasCreated->getName();
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
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
        return 'apply'.\implode(\array_slice(\explode('\\', \get_class($e)), -1));
    }
}
