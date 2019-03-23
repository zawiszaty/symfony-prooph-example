<?php

declare(strict_types=1);

namespace Tests\Application\Command\Category\Create;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Category\Delete\DeleteCategoryCommand;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Common\CommandHandler\CommandBus;
use Tests\TestCase;

class DeleteCategoryHandlerTest extends TestCase
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
        $command = new DeleteCategoryCommand($category->getId());
        $this->commandBus->handle($command);
        $this->assertSame(1, 1);
    }
}
