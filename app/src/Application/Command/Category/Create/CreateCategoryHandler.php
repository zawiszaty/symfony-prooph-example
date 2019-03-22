<?php

declare(strict_types=1);

namespace App\Application\Command\Category\Create;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class CreateCategoryHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryStore
     */
    private $categoryStoreRepository;

    public function __construct(CategoryStore $categoryStoreRepository)
    {
        $this->categoryStoreRepository = $categoryStoreRepository;
    }

    public function __invoke(CreateCategoryCommand $command): void
    {
        $category = Category::create(
            AggregateRootId::generate(),
            Name::fromString($command->getName())
        );
        $this->categoryStoreRepository->save($category);
    }
}
