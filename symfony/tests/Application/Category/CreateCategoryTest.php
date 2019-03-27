<?php

declare(strict_types=1);

namespace Tests\Application\Category;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use Tests\TestCase;

class CreateCategoryTest extends TestCase
{
    public function test_it_create_category()
    {
        $command = new CreateCategoryCommand('test');
        $this->commandBus->handle($command);
        /** @var CategoryView $category */
        $category = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test']);
        $this->assertSame($category->getName(), 'test');
    }
}
