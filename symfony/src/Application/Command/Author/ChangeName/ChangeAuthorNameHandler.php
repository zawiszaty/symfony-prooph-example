<?php

declare(strict_types=1);

namespace App\Application\Command\Author\ChangeName;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class ChangeAuthorNameHandler implements CommandHandlerInterface
{
    /**
     * @var AuthorStore
     */
    private $authorStore;

    public function __construct(AuthorStore $authorStore)
    {
        $this->authorStore = $authorStore;
    }

    public function __invoke(ChangeAuthorNameCommand $command): void
    {
        /** @var Author $author */
        $author = $this->authorStore->get(AggregateRootId::fromString($command->getId()));
        $author->changeName($command->getName());
        $this->authorStore->save($author);
    }
}
