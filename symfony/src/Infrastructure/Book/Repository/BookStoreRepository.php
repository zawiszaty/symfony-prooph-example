<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Repository;

use App\Domain\Book\Book;
use App\Domain\Book\BookStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateTranslator;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\StreamName;
use Prooph\SnapshotStore\SnapshotStore;

class BookStoreRepository extends AggregateRepository implements BookStore
{
    public function __construct(EventStore $eventStore, AggregateType $aggregateType, AggregateTranslator $aggregateTranslator, SnapshotStore $snapshotStore = null, StreamName $streamName = null, bool $oneStreamPerAggregate = false, bool $disableIdentityMap = false, array $metadata = [])
    {
        parent::__construct($eventStore, $aggregateType, $aggregateTranslator, $snapshotStore, $streamName, $oneStreamPerAggregate, $disableIdentityMap, $metadata);
    }

    public function save(Book $todo): void
    {
        $this->saveAggregateRoot($todo);
    }

    public function get(AggregateRootId $todoId): ?Book
    {
        /** @var Book $book */
        $book = $this->getAggregateRoot($todoId->toString());

        return $book;
    }
}
