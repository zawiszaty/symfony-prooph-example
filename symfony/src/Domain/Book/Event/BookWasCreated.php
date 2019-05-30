<?php

declare(strict_types=1);

namespace App\Domain\Book\Event;

use App\Domain\Book\ValueObject\Description;
use App\Domain\Common\Adapter\UuidAdapter;
use App\Domain\Common\Adapter\UuidAdapterInterface;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use Prooph\EventSourcing\AggregateChanged;

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
     * @var UuidAdapterInterface
     */
    private $category;

    /**
     * @var UuidAdapterInterface
     */
    private $author;

    public static function createWithData(AggregateRootId $id, Name $name, Description $description, UuidAdapterInterface $category, UuidAdapterInterface $author): self
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
     * @return UuidAdapterInterface
     */
    public function getCategory(): UuidAdapterInterface
    {
        if (null === $this->category) {
            $this->category = UuidAdapter::fromString($this->payload['category']);
        }

        return $this->category;
    }

    /**
     * @return UuidAdapterInterface
     */
    public function getAuthor(): UuidAdapterInterface
    {
        if (null === $this->author) {
            $this->author = UuidAdapter::fromString($this->payload['category']);
        }

        return $this->author;
    }
}
