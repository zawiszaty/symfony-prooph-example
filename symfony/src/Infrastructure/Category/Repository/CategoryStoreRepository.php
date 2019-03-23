<?php

declare(strict_types=1);

namespace App\Infrastructure\Category\Repository;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateTranslator;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventStore\Pdo\MySqlEventStore;

class CategoryStoreRepository extends AggregateRepository implements CategoryStore
{
    /**
     * CategoryStoreRepository constructor.
     */
    public function __construct(MySqlEventStore $eventStore, AggregateTranslator $aggregateTranslator)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromString(Category::class),
            $aggregateTranslator
        );
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
