<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Repository;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateTranslator;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventStore\Pdo\MySqlEventStore;

class AuthorStoreRepository extends AggregateRepository implements AuthorStore
{
    /**
     * CategoryStoreRepository constructor.
     */
    public function __construct(MySqlEventStore $eventStore, AggregateTranslator $aggregateTranslator)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromAggregateRootClass(Author::class),
            $aggregateTranslator
        );
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
