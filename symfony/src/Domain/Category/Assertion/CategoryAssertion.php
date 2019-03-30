<?php

declare(strict_types=1);

namespace App\Domain\Category\Assertion;

use App\Domain\Category\Category;
use App\Domain\Category\Exception\CategoryNotExistException;

class CategoryAssertion
{
    public static function exist(?Category $category)
    {
        if (!$category) {
            throw new CategoryNotExistException();
        }
    }
}
