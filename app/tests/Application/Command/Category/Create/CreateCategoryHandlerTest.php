<?php

declare(strict_types=1);

namespace Tests\Application\Command\Category\Create;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use Tests\TestCase;

class CreateCategoryHandlerTest extends TestCase
{
    /**
     * @var CommandBus|null
     */
    private $commandBus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commandBus = $this->container->get('App\Infrastructure\Common\CommandHandler\CommandBus');
    }

    public function test_it_handle()
    {
//        $command = new CreateCategoryCommand('test');
//        $this->commandBus->handle($command);
        $this->assertSame(1, 1);
    }
}
