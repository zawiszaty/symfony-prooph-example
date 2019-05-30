<?php

declare(strict_types=1);

namespace App\Application\Command\Category\Delete;

use App\Application\Command\Book\Delete\DeleteBookCommand;
use App\Domain\Category\Assertion\CategoryAssertion;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryStore;
use App\Domain\Category\Exception\CategoryNotExistException;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Book\Query\Projections\BookMysqlRepository;
use App\Infrastructure\Book\Query\Projections\BookView;
use App\Infrastructure\Common\CommandHandler\CommandBus;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;
use App\Infrastructure\Common\CommandHandler\Exception\HandlerNotFoundException;

class DeleteCategoryHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryStore
     */
    private $categoryStoreRepository;
    /**
     * @var BookMysqlRepository
     */
    private $bookRepository;
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CategoryStore $categoryStoreRepository, BookMysqlRepository $bookRepository, CommandBus $commandBus)
    {
        $this->categoryStoreRepository = $categoryStoreRepository;
        $this->bookRepository = $bookRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param DeleteCategoryCommand $command
     *
     * @throws CategoryNotExistException
     * @throws HandlerNotFoundException
     */
    public function __invoke(DeleteCategoryCommand $command)
    {
        /** @var Category $category */
        $category = $this->categoryStoreRepository->get(AggregateRootId::withId($command->getId()));
        CategoryAssertion::exist($category);
        $books = $this->bookRepository->getAllByAuthorId($command->getId());
        /** @var BookView $book */
        foreach ($books as $book) {
            $command = new DeleteBookCommand($book->getId());
            $this->commandBus->handle($command);
        }
        $category->delete();
        $this->categoryStoreRepository->save($category);
    }
}
