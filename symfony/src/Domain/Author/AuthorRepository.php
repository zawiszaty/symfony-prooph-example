<?php

declare(strict_types=1);

namespace App\Domain\Author;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Author\Query\Projections\AuthorView;

interface AuthorRepository
{
    public function add(AuthorView $authorView): void;

    public function oneByUuid(AggregateRootId $id): AuthorView;

    public function find(string $id): AuthorView;

    public function delete(string $id): void;

    public function apply(): void;
}
