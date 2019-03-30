<?php

declare(strict_types=1);

namespace App\Domain\Book\Event;

use App\Domain\Book\ValueObject\Description;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use Prooph\EventSourcing\AggregateChanged;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class BookWasCreated extends AggregateChanged
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var Description
     */
    private $description;

    /**
     * @var UuidInterface
     */
    private $category;

    /**
     * @var UuidInterface
     */
    private $author;

    public static function createWithData(AggregateRootId $id, Name $name, Description $description, UuidInterface $category, UuidInterface $author): self
    {
        /** @var self $event */
        $event = self::occur($id->toString(), [
            'name' => $name->toString(),
            'description' => $description->toString(),
            'category' => $category->toString(),
            'author' => $author->toString(),
        ]);

        $event->id = $id;
        $event->name = $name;
        $event->description = $description;
        $event->author = $author;
        $event->category = $category;

        return $event;
    }

    public function getId(): AggregateRootId
    {
        if (null === $this->id) {
            $this->id = AggregateRootId::fromString($this->aggregateId());
        }

        return $this->id;
    }

    public function getName(): Name
    {
        if (null === $this->name) {
            $this->name = Name::fromString($this->payload['name']);
        }

        return $this->name;
    }

    public function getDescription(): Description
    {
        if (null === $this->description) {
            $this->description = Description::fromString($this->payload['description']);
        }

        return $this->description;
    }

    /**
     * @return UuidInterface
     */
    public function getCategory(): UuidInterface
    {
        if (null === $this->category) {
            $this->category = Uuid::fromString($this->payload['category']);
        }

        return $this->category;
    }

    /**
     * @return UuidInterface
     */
    public function getAuthor(): UuidInterface
    {
        if (null === $this->author) {
            $this->author = Uuid::fromString($this->payload['author']);
        }

        return $this->author;
    }
}
