<?php

declare(strict_types=1);

namespace App\Application\Command\Category\ChangeName;

use App\Domain\Category\Assertion\CategoryAssertion;
use App\Domain\Category\Category;
use App\Domain\Category\CategoryStore;
use App\Domain\Category\Exception\CategoryNotExistException;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use App\Infrastructure\Category\Validator\CategoryValidator;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class ChangeCategoryNameHandler implements CommandHandlerInterface
{
    /**
     * @var CategoryStore
     */
    private $categoryStoreRepository;
    /**
     * @var CategoryValidator
     */
    private $categoryValidator;

    public function __construct(
        CategoryStore $categoryStoreRepository,
        CategoryValidator $categoryValidator
    ) {
        $this->categoryStoreRepository = $categoryStoreRepository;
        $this->categoryValidator = $categoryValidator;
    }

    /**
     * @param ChangeCategoryNameCommand $command
     *
     * @throws CategoryNotExistException
     */
    public function __invoke(ChangeCategoryNameCommand $command): void
    {
        $this->categoryValidator->categoryNameExist($command->getName());
        /** @var Category $category */
        $category = $this->categoryStoreRepository->get(AggregateRootId::fromString($command->getId()));
        CategoryAssertion::exist($category);
        $category->changeName(Name::fromString($command->getName()));
        $this->categoryStoreRepository->save($category);
    }
}
