<?php

declare(strict_types=1);

namespace App\Domain\Category;

use App\Domain\Category\Events\CategoryWasCreated;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;
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

    public static function create(AggregateRootId $generate, Name $name)
    {
        $self = new self();
        $self->recordThat(CategoryWasCreated::createWithData($generate, $name));

        return $self;
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
