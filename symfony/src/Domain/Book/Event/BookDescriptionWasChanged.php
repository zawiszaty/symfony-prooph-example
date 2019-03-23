<?php

declare(strict_types=1);

namespace App\Domain\Book\Event;

use App\Domain\Book\ValueObject\Description;
use App\Domain\Common\ValueObject\AggregateRootId;
use Prooph\EventSourcing\AggregateChanged;

final class BookDescriptionWasChanged extends AggregateChanged
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * @var Description
     */
    private $description;

    public static function createWithData(AggregateRootId $id, Description $description): self
    {
        /** @var self $event */
        $event = self::occur($id->toString(), [
            'description' => $description->toString(),
        ]);

        $event->id = $id;
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

    public function getDescription(): Description
    {
        if (null === $this->description) {
            $this->description = Description::fromString($this->payload['description']);
        }

        return $this->description;
    }
}
