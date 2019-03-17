<?php

declare(strict_types=1);

namespace App\Infrastructure\Common\QueryHandler;

use App\Infrastructure\Common\CommandHandler\Exception\HandlerNotFoundException;

/**
 * Class CommandBus.
 */
class QueryBus
{
    /**
     * @var array
     */
    private $queryHandler = [];

    public function addQueryHandler(object $query)
    {
        $this->queryHandler[\get_class($query)] = $query;
    }

    /**
     * @param object $command
     *
     * @throws HandlerNotFoundException
     */
    public function handle(object $command): void
    {
        $handler = $this->commandToHandler(\get_class($command));
        /* @var QueryBusAbstract $handler */
        $handler($command);
    }

    /**
     * @param string $command
     *
     * @return QueryHandlerInterface
     *
     * @throws HandlerNotFoundException
     */
    private function commandToHandler(string $command): QueryHandlerInterface
    {
        $queryHandler = explode('\\', $command);
        $queryHandler[count($queryHandler) - 1] = str_replace('Query', 'Handler', $queryHandler[count($queryHandler) - 1]);
        $queryHandler = implode('\\', $queryHandler);
        $handler = $this->queryHandler[$queryHandler];

        if (!$handler instanceof QueryHandlerInterface) {
            throw new HandlerNotFoundException('Handler not found from: '.$command);
        }

        return $handler;
    }
}
