<?php

declare(strict_types=1);

namespace App\Domain\Book\Exception;

class BookNotFoundException extends \Exception
{
    protected $message = 'Book Not Found';
    protected $code = 404;
}
