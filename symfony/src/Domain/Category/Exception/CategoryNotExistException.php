<?php

declare(strict_types=1);

namespace App\Domain\Category\Exception;

class CategoryNotExistException extends \Exception
{
    protected $message = 'Category Not Found';
    protected $code = '404';
}
