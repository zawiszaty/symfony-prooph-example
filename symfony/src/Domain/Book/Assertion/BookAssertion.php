<?php

declare(strict_types=1);

namespace App\Domain\Book\Assertion;

use App\Domain\Book\Book;
use App\Domain\Book\Exception\BookNotFoundException;

class BookAssertion
{
    public static function exist(?Book $book)
    {
        if (!$book) {
            throw new BookNotFoundException();
        }
    }
}
