<?php

declare(strict_types=1);

namespace App\Domain\Book;

interface BookRepository
{
    public function getAllByAuthorId(string $name): array;
}
