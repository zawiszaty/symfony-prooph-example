<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Repository;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateTranslator;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\StreamName;
use Prooph\SnapshotStore\SnapshotStore;

class AuthorStoreRepository extends AggregateRepository implements AuthorStore
{
    public function __construct(EventStore $eventStore, AggregateType $aggregateType, AggregateTranslator $aggregateTranslator, SnapshotStore $snapshotStore = null, StreamName $streamName = null, bool $oneStreamPerAggregate = false, bool $disableIdentityMap = false, array $metadata = [])
    {
        parent::__construct($eventStore, $aggregateType, $aggregateTranslator, $snapshotStore, $streamName, $oneStreamPerAggregate, $disableIdentityMap, $metadata);
    }

    public function save(Author $todo): void
    {
        $this->saveAggregateRoot($todo);
    }

    public function get(AggregateRootId $todoId): ?Author
    {
        /** @var Author $author */
        $author = $this->getAggregateRoot($todoId->toString());

        return $author;
    }
}
