<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Repository;

use App\Domain\Book\Book;
use App\Domain\Common\ValueObject\AggregateRootId;
use Prooph\EventSourcing\Aggregate\AggregateRepository;

class BookStoreRepository extends AggregateRepository
{
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
