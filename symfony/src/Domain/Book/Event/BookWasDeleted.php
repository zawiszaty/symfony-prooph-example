<?php

declare(strict_types=1);

namespace App\Domain\Book\Event;

use App\Domain\Common\ValueObject\AggregateRootId;
use Prooph\EventSourcing\AggregateChanged;

final class BookWasDeleted extends AggregateChanged
{
    /**
     * @var AggregateRootId
     */
    private $id;

    public static function createWithData(AggregateRootId $id): self
    {
        /** @var self $event */
        $event = self::occur($id->toString(), []);
        $event->id = $id;

        return $event;
    }

    public function getId(): AggregateRootId
    {
        if (null === $this->id) {
            $this->id = AggregateRootId::fromString($this->aggregateId());
        }

        return $this->id;
    }
}
