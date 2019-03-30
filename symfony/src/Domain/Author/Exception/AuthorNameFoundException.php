<?php

declare(strict_types=1);

namespace App\Domain\Author\Exception;

class AuthorNameFoundException extends \Exception
{
    protected $message = 'Author Name Found';

    protected $code = 404;
}
