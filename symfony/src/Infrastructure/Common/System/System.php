<?php

declare(strict_types=1);

namespace App\Infrastructure\Common\System;

use App\Infrastructure\Common\CommandHandler\CommandBus;
use App\Infrastructure\Common\QueryHandler\QueryBus;
use Doctrine\DBAL\Connection;

/**
 * Class System.
 */
class System
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var QueryBus
     */
    private $queryBus;
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(CommandBus $commandBus, QueryBus $queryBus, Connection $connection)
    {
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->connection = $connection;
    }

    public function handle(object $command)
    {
        $this->commandBus->handle($command);
    }

    public function ask(object $query): array
    {
        $data = $this->queryBus->handle($query);

        return $data;
    }
}
