<?php

declare(strict_types=1);

namespace App\Infrastructure\Common\CommandHandler;

class CommandBusAbstract
{
    public function __invoke($command): void
    {
    }
}
