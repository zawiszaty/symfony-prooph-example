<?php

declare(strict_types=1);

namespace Tests\Application\Book;

use App\Application\Command\Author\Create\CreateAuthorCommand;
use App\Application\Command\Book\Create\CreateBookCommand;
use App\Application\Command\Category\Create\CreateCategoryCommand;
use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Book\Query\Projections\BookView;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use Tests\TestCase;

class CreateBookTest extends TestCase
{
    public function test_it_create_book()
    {
        $command = new CreateCategoryCommand('test');
        $this->commandBus->handle($command);
        /** @var CategoryView $category */
        $category = $this->manager->getRepository(CategoryView::class)->findOneBy(['name' => 'test']);
        $command = new CreateAuthorCommand('test');
        $this->commandBus->handle($command);
        /** @var AuthorView $author */
        $author = $this->manager->getRepository(AuthorView::class)->findOneBy(['name' => 'test']);
        $command = new CreateBookCommand('test', 'test', $category->getId(), $author->getId());
        $this->commandBus->handle($command);
        /** @var BookView $book */
        $book = $this->manager->getRepository(BookView::class)->findOneBy(['name' => 'test']);
        $this->assertSame($book->getName(), 'test');
        $this->assertSame($book->getDescription(), 'test');
        $this->assertSame($book->getAuthor()->getId(), $author->getId());
        $this->assertSame($book->getCategory()->getId(), $category->getId());
    }
}
