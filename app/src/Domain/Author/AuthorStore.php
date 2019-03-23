<?php

declare(strict_types=1);

namespace App\Domain\Author;

use App\Domain\Common\ValueObject\AggregateRootId;

interface AuthorStore
{
    public function save(Author $todo): void;

    public function get(AggregateRootId $todoId): ?Author;
}
