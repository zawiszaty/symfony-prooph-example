<?php

declare(strict_types=1);

namespace App\Domain\Category;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Category\Query\Projections\CategoryView;

interface CategoryRepository
{
    public function add(CategoryView $postView): void;

    public function oneByUuid(AggregateRootId $id): CategoryView;

    public function delete(string $id): void;

    public function apply(): void;

    public function find(string $id): ?CategoryView;

    public function findOneBy(array $query): ?CategoryView;
}
