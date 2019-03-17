<?php

declare(strict_types=1);

namespace App\Domain\Author;

use App\Domain\Author\Events\AuthorNameWasChanged;
use App\Domain\Author\Events\AuthorWasCreated;
use App\Domain\Author\Events\AuthorWasDeleted;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\EventSourcing\AggregateChanged;

class Author extends AggregateRoot
{
    /**
     * @var AggregateRootId
     */
    protected $id;

    /**
     * @var Name
     */
    protected $name;

    public static function create(AggregateRootId $generate, Name $name): Author
    {
        $author = new self();
        $author->recordThat(AuthorWasCreated::createWithData($generate, $name));

        return $author;
    }

    public function changeName(string $string): void
    {
        $this->name->changeName($string);
        $this->recordThat(AuthorNameWasChanged::createWithData($this->id, $this->name));
    }

    public function delete()
    {
        $this->recordThat(AuthorWasDeleted::createWithData($this->id));
    }

    public function applyAuthorWasDeleted(AuthorWasDeleted $authorWasDeleted)
    {
    }

    protected function applyAuthorNameWasChanged(AuthorNameWasChanged $authorNameWasChanged)
    {
        $this->name = $authorNameWasChanged->getName();
    }

    protected function applyAuthorWasCreated(AuthorWasCreated $authorWasCreated): void
    {
        $this->id = $authorWasCreated->getId();
        $this->name = $authorWasCreated->getName();
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

    /**
     * @return AggregateRootId
     */
    public function getId(): AggregateRootId
    {
        return $this->id;
    }

    /**
     * @return Name
     */
    public function getName(): Name
    {
        return $this->name;
    }
}
