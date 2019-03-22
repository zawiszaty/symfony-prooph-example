<?php

declare(strict_types=1);

namespace Tests\Application\Command\Category\Create;

use App\Application\Command\Category\ChangeName\ChangeCategoryNameCommand;
use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use Assert\Assertion;
use Tests\TestCase;

class ChangeNameCategoryHandlerTest extends TestCase
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
        $name = 'test';
        $command = new CreateCategoryCommand($name);
        $this->commandBus->handle($command);
        $name = 'test2';
        $command = new ChangeCategoryNameCommand($name);
        $this->commandBus->handle($command);
        $this->assertTrue(true);
    }
}
