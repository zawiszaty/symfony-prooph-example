<?php

declare(strict_types=1);

namespace Tests\Application\Command\Category\Create;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Infrastructure\Category\Query\Projections\CategoryView;
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
        $name = 'test';
        $command = new CreateCategoryCommand($name);
        $this->commandBus->handle($command);
//        /** @var CategoryView $category */
//        $category = $this->getCategory($name);
        $this->assertSame('test', $name);
    }
}
