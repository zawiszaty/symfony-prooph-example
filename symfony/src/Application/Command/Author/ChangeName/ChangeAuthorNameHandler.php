<?php

declare(strict_types=1);

namespace App\Application\Command\Author\ChangeName;

use App\Domain\Author\Assertion\AuthorAssertion;
use App\Domain\Author\Author;
use App\Domain\Author\AuthorStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use App\Infrastructure\Author\Validator\AuthorValidator;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class ChangeAuthorNameHandler implements CommandHandlerInterface
{
    /**
     * @var AuthorStore
     */
    private $authorStore;
    /**
     * @var AuthorValidator
     */
    private $authorValidator;

    public function __construct(
        AuthorStore $authorStore,
        AuthorValidator $authorValidator
    ) {
        $this->authorStore = $authorStore;
        $this->authorValidator = $authorValidator;
    }

    public function __invoke(ChangeAuthorNameCommand $command): void
    {
        $this->authorValidator->authorNameExist($command->getName());
        /** @var Author $author */
        $author = $this->authorStore->get(AggregateRootId::fromString($command->getId()));
        AuthorAssertion::exist($author);
        $author->changeName(Name::fromString($command->getName()));
        $this->authorStore->save($author);
    }
}
