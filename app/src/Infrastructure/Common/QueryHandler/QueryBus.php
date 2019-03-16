<?php

declare(strict_types=1);

namespace App\Infrastructure\Common\QueryHandler;

use App\Infrastructure\Common\CommandHandler\Exception\HandlerNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CommandBus.
 */
class QueryBus
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * CommandBus constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
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
        $commandHandler = str_replace('Query', 'Handler', $command);

        if (!$this->container->has($commandHandler)) {
            throw new HandlerNotFoundException('Handler not found from: '.$command);
        }
        $handler = $this->container->get($commandHandler);

        if (!$handler instanceof QueryHandlerInterface) {
            throw new HandlerNotFoundException('Handler not found from: '.$command);
        }

        return $handler;
    }
}
