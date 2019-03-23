<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Repository;

use App\Domain\Book\Book;
use App\Domain\Common\ValueObject\AggregateRootId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateTranslator;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventStore\Pdo\MySqlEventStore;

class BookStoreRepository extends AggregateRepository
{
    /**
     * CategoryStoreRepository constructor.
     */
    public function __construct(MySqlEventStore $eventStore, AggregateTranslator $aggregateTranslator)
    {
        parent::__construct(
            $eventStore,
            AggregateType::fromString(Book::class),
            $aggregateTranslator
        );
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
