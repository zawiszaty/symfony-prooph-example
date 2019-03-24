<?php

declare(strict_types=1);

namespace App\Application\Command\Author\Create;

use App\Domain\Author\Author;
use App\Domain\Author\AuthorStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use App\Infrastructure\Author\Repository\AuthorStoreRepository;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class CreateAuthorHandler implements CommandHandlerInterface
{
    /**
     * @var AuthorStore
     */
    private $authorStoreRepository;

    public function __construct(AuthorStore $authorStoreRepository)
    {
        $this->authorStoreRepository = $authorStoreRepository;
    }

    public function __invoke(CreateAuthorCommand $command): void
    {
        $author = Author::create(
            AggregateRootId::generate(),
            Name::fromString($command->getName())
        );
        $this->authorStoreRepository->save($author);
    }
}
