<?php

declare(strict_types=1);

namespace App\Domain\Author\Assertion;

use App\Domain\Author\Author;
use App\Domain\Author\Exception\AuthorNotFoundException;

class AuthorAssertion
{
    public static function exist(?Author $author)
    {
        if (!$author) {
            throw new AuthorNotFoundException();
        }
    }
}
