<?php

declare(strict_types=1);

namespace App\Domain\Category\Events;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use Prooph\EventSourcing\AggregateChanged;

final class CategoryNameWasChanged extends AggregateChanged
{
    /**
     * @var AggregateRootId
     */
    private $id;

    /**
     * @var Name
     */
    private $name;

    public static function createWithData(AggregateRootId $id, Name $name): self
    {
        /** @var self $event */
        $event = self::occur($id->toString(), [
            'name' => $name->toString(),
        ]);

        $event->id = $id;
        $event->name = $name;

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
}
