<?php

declare(strict_types=1);

namespace App\Unit\Infrastructure\Common\CommandHandler;

use App\Infrastructure\Common\CommandHandler\CommandBus;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;
use App\Unit\TestCase;

class TestCommand
{
}

class TestHandler implements CommandHandlerInterface
{
    public function __invoke(TestCommand $command): void
    {
    }
}

class CommandBusTest extends TestCase
{
    /**
     * @var CommandBus|null
     */
    private $commandBus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commandBus = $this->container->get('App\Infrastructure\Common\CommandHandler\CommandBus');
        $this->container->set('App\Unit\Infrastructure\Common\HandlerHandler\TestHandler', new TestHandler());
    }

    public function testHandle()
    {
        $this->commandBus->handle(new TestCommand());
        $this->assertSame(1, 1);
    }
}
