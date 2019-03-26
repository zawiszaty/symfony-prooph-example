<?php

declare(strict_types=1);

namespace App\Infrastructure\Category\Repository;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateTranslator;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\StreamName;
use Prooph\SnapshotStore\SnapshotStore;

class CategoryStoreRepository extends AggregateRepository implements CategoryStore
{
    public function __construct(EventStore $eventStore, AggregateType $aggregateType, AggregateTranslator $aggregateTranslator, SnapshotStore $snapshotStore = null, StreamName $streamName = null, bool $oneStreamPerAggregate = false, bool $disableIdentityMap = false, array $metadata = [])
    {
        parent::__construct($eventStore, $aggregateType, $aggregateTranslator, $snapshotStore, $streamName, $oneStreamPerAggregate, $disableIdentityMap, $metadata);
    }

    public function save(Category $todo): void
    {
        $this->saveAggregateRoot($todo);
    }

    public function get(AggregateRootId $todoId): ?Category
    {
        /** @var Category $category */
        $category = $this->getAggregateRoot($todoId->toString());

        return $category;
    }
}
