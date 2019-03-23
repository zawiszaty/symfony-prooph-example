<?php

declare(strict_types=1);

namespace App\Application\Command\Category\ChangeName;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class ChangeCategoryNameHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryStore
     */
    private $categoryStoreRepository;

    public function __construct(CategoryStore $categoryStoreRepository)
    {
        $this->categoryStoreRepository = $categoryStoreRepository;
    }

    public function __invoke(ChangeCategoryNameCommand $command): void
    {
        /** @var Category $category */
        $category = $this->categoryStoreRepository->get(AggregateRootId::fromString($command->getId()));
        $category->changeName($command->getName());
        $this->categoryStoreRepository->save($category);
    }
}
