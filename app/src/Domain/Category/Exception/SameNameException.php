<?php

declare(strict_types=1);

namespace App\Domain\Category\Exception;

class SameNameException extends \Exception
{
    protected $message = 'Changed Name was the same';
}
