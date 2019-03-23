<?php

declare(strict_types=1);

namespace Tests\Application\Command\Category\Create;

use App\Application\Command\Category\ChangeName\ChangeCategoryNameCommand;
use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Common\CommandHandler\CommandBus;
use Tests\TestCase;

class ChangeCategoryNameHandlerTest extends TestCase
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
        /** @var CategoryView $category */
        $category = $this->getCategory($name);
        $name = 'test2';
        $command = new ChangeCategoryNameCommand($category->getId(), $name);
        $this->commandBus->handle($command);
        /** @var CategoryView $category */
        $newCategory = $this->getCategory('test2');
        $this->assertSame('test2', 'test2');
    }
}
