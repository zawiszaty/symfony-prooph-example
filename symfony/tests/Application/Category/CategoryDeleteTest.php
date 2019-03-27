<?php

declare(strict_types=1);

namespace Tests\Application\Category;

use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Application\Command\Category\Delete\DeleteCategoryCommand;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use Tests\TestCase;

class CategoryDeleteTest extends TestCase
{
    public function test_it_category_delete()
    {
        $command = new CreateCategoryCommand('test');
        $this->commandBus->handle($command);
        /** @var CategoryView $category */
        $category = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test']);
        $command = new DeleteCategoryCommand($category->getId());
        $this->commandBus->handle($command);
        /** @var CategoryView $category */
        $category = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test']);
        $this->assertSame($category, null);
    }
}
