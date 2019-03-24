<?php

declare(strict_types=1);

namespace App\Domain\Book;

use App\Domain\Common\ValueObject\AggregateRootId;

interface BookStore
{
    public function save(Book $todo): void;

    public function get(AggregateRootId $todoId): ?Book;
}
