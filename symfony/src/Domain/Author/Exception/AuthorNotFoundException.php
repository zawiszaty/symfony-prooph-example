<?php

declare(strict_types=1);

namespace App\Domain\Author\Exception;

class AuthorNotFoundException extends \Exception
{
    protected $message = 'Author Not Found';

    protected $code = 404;
}
