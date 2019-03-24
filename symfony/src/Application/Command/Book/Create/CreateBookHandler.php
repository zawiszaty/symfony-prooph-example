<?php

declare(strict_types=1);

namespace App\Application\Command\Book\Create;

use App\Domain\Book\Book;
use App\Domain\Book\BookStore;
use App\Domain\Book\ValueObject\Description;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class CreateBookHandler implements CommandHandlerInterface
{
    /**
     * @var BookStore
     */
    private $bookStore;

    public function __construct(BookStore $bookStore)
    {
        $this->bookStore = $bookStore;
    }

    public function __invoke(CreateBookCommand $command)
    {
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString($command->getName()),
            Description::fromString($command->getDescription()),
            $command->getCategory(),
            $command->getAuthor()
        );
        $this->bookStore->save($book);
    }
}
