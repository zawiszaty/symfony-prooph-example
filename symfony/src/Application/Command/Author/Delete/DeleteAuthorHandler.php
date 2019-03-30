<?php

declare(strict_types=1);

namespace App\Application\Command\Author\Delete;

use App\Application\Command\Book\Delete\DeleteBookCommand;
use App\Domain\Author\Assertion\AuthorAssertion;
use App\Domain\Author\Author;
use App\Domain\Author\AuthorStore;
use App\Domain\Author\Exception\AuthorNotFoundException;
use App\Domain\Book\BookRepository;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Book\Query\Projections\BookView;
use App\Infrastructure\Common\CommandHandler\CommandBus;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;
use App\Infrastructure\Common\CommandHandler\Exception\HandlerNotFoundException;

class DeleteAuthorHandler implements CommandHandlerInterface
{
    /**
     * @var AuthorStore
     */
    private $authorStore;
    /**
     * @var BookRepository
     */
    private $bookRepository;
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(AuthorStore $authorStore, BookRepository $bookRepository, CommandBus $commandBus)
    {
        $this->authorStore = $authorStore;
        $this->bookRepository = $bookRepository;
        $this->commandBus = $commandBus;
    }

    /**
     * @param DeleteAuthorCommand $command
     *
     * @throws AuthorNotFoundException
     * @throws HandlerNotFoundException
     */
    public function __invoke(DeleteAuthorCommand $command): void
    {
        /** @var Author $author */
        $author = $this->authorStore->get(AggregateRootId::fromString($command->getId()));
        AuthorAssertion::exist($author);
        $books = $this->bookRepository->getAllByAuthorId($command->getId());
        /** @var BookView $book */
        foreach ($books as $book) {
            $command = new DeleteBookCommand($book->getId());
            $this->commandBus->handle($command);
        }
        $author->delete();
        $this->authorStore->save($author);
    }
}
