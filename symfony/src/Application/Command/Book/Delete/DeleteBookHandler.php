<?php

declare(strict_types=1);

namespace App\Application\Command\Book\Delete;

use App\Domain\Book\Book;
use App\Domain\Book\BookStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class DeleteBookHandler implements CommandHandlerInterface
{
    /**
     * @var BookStore
     */
    private $bookStore;

    public function __construct(BookStore $bookStore)
    {
        $this->bookStore = $bookStore;
    }

    public function __invoke(DeleteBookCommand $command)
    {
        /** @var Book $book */
        $book = $this->bookStore->get(AggregateRootId::fromString($command->getId()));
        $book->delete();
        $this->bookStore->save($book);
    }
}
