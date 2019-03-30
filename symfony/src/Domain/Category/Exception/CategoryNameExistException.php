<?php

declare(strict_types=1);

namespace App\Domain\Category\Exception;

class CategoryNameExistException extends \Exception
{
    protected $message = 'Category Name Found';
    protected $code = '404';
}
