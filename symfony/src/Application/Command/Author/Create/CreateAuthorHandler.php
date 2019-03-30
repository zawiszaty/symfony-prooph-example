<?php

declare(strict_types=1);

namespace App\Application\Command\Author\Create;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use App\Infrastructure\Author\Validator\AuthorValidator;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class CreateAuthorHandler implements CommandHandlerInterface
{
    /**
     * @var AuthorStore
     */
    private $authorStoreRepository;
    /**
     * @var AuthorValidator
     */
    private $authorValidator;

    public function __construct(
        AuthorStore $authorStoreRepository,
        AuthorValidator $authorValidator
    ) {
        $this->authorStoreRepository = $authorStoreRepository;
        $this->authorValidator = $authorValidator;
    }

    public function __invoke(CreateAuthorCommand $command): void
    {
        $this->authorValidator->authorNameExist($command->getName());
        $author = Author::create(
            AggregateRootId::generate(),
            Name::fromString($command->getName())
        );
        $this->authorStoreRepository->save($author);
    }
}
