<?php

declare(strict_types=1);

namespace App\Domain\Category;

use App\Domain\Common\ValueObject\AggregateRootId;

interface CategoryStore
{
    public function save(Category $todo): void;

    public function get(AggregateRootId $todoId): ?Category;
}
