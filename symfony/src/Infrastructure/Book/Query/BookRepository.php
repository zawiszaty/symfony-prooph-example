<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Query;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Book\Query\Projections\BookView;

interface BookRepository
{
    public function getAllByAuthorId(string $name): array;

    public function add(BookView $postView): void;

    public function oneByUuid(AggregateRootId $id): BookView;

    public function delete(string $id): void;

    public function apply(): void;
}
