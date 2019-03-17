<?php

declare(strict_types=1);

namespace App\Application\Command\Category\Create;

use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class CreateCategoryHandler implements CommandHandlerInterface
{
    public function __invoke(CreateCategoryCommand $command)
    {
        var_dump('test');
    }
}
