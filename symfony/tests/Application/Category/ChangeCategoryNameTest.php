<?php

declare(strict_types=1);

namespace Tests\Application\Category;

use App\Application\Command\Category\ChangeName\ChangeCategoryNameCommand;
use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use Tests\TestCase;

class ChangeCategoryNameTest extends TestCase
{
    public function test_it_change_category_name()
    {
        $command = new CreateCategoryCommand('test');
        $this->commandBus->handle($command);
        /** @var CategoryView $category */
        $category = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test']);
        $command = new ChangeCategoryNameCommand($category->getId(), 'test2');
        $this->commandBus->handle($command);
        /** @var CategoryView $category */
        $category = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test2']);
        $this->assertSame($category->getName(), 'test2');
    }
}
