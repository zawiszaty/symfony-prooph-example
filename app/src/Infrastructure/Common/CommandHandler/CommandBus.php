<?php

declare(strict_types=1);

namespace App\Infrastructure\Common\CommandHandler;

use App\Infrastructure\Common\CommandHandler\Exception\HandlerNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class CommandBus.
 */
class CommandBus
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
        /* @var CommandBusAbstract $handler */
        $handler($command);
    }

    /**
     * @param string $command
     *
     * @return CommandHandlerInterface
     *
     * @throws HandlerNotFoundException
     */
    private function commandToHandler(string $command): CommandHandlerInterface
    {
        $commandHandler = str_replace('Command', 'Handler', $command);

        if (!$this->container->has($commandHandler)) {
            throw new HandlerNotFoundException('Handler not found from: '.$command);
        }
        $handler = $this->container->get($commandHandler);

        if (!$handler instanceof CommandHandlerInterface) {
            throw new HandlerNotFoundException('Handler not found from: '.$command);
        }

        return $handler;
    }
}
