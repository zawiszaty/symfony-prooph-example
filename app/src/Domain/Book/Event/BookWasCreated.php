<?php

namespace App\Domain\Book\Event;

use App\Domain\Book\ValueObject\Description;
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

    public static function createWithData(AggregateRootId $id, Name $name, Description $description): self
    {
        /** @var self $event */
        $event = self::occur($id->toString(), [
            'name' => $name->toString(),
            'description' => $description->toString(),
        ]);

        $event->id = $id;
        $event->name = $name;
        $event->description = $description;

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
}