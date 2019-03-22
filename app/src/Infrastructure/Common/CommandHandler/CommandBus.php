<?php

declare(strict_types=1);

namespace App\Infrastructure\Common\CommandHandler;

use App\Infrastructure\Common\CommandHandler\Exception\HandlerNotFoundException;

/**
 * Class CommandBus.
 */
class CommandBus
{
    /**
     * @var array
     */
    private $handlers = [];

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

    public function addCommandHandler(object $handler): void
    {
        $this->handlers[\get_class($handler)] = $handler;
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
        $commandHandler = explode('\\', $command);
        $commandHandler[count($commandHandler) - 1] = str_replace('Command', 'Handler', $commandHandler[count($commandHandler) - 1]);
        $commandHandler = implode('\\', $commandHandler);
        $handler = $this->handlers[$commandHandler];

        if (!$handler instanceof CommandHandlerInterface) {
            throw new HandlerNotFoundException('Handler not found from: '.$command);
        }

        return $handler;
    }
}
