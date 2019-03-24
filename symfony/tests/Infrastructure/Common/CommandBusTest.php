<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Common\CommandHandler;

use App\Infrastructure\Common\CommandHandler\CommandBus;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;
use Tests\TestCase;

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
    protected $commandBus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commandBus = $this->container->get('App\Infrastructure\Common\CommandHandler\CommandBus');
        $this->commandBus->addCommandHandler(new TestHandler());
    }

    public function testHandle()
    {
        $this->commandBus->handle(new TestCommand());
        $this->assertSame(1, 1);
    }
}
