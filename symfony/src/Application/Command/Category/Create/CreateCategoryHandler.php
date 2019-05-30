<?php

declare(strict_types=1);

namespace App\Application\Command\Category\Create;

use App\Domain\Category\Category;
use App\Domain\Category\CategoryStore;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use App\Infrastructure\Category\Validator\CategoryValidator;
use App\Infrastructure\Common\CommandHandler\CommandHandlerInterface;

class CreateCategoryHandler implements CommandHandlerInterface
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

    public function __invoke(CreateCategoryCommand $command): void
    {
        $this->categoryValidator->categoryNameExist($command->getName());
        $category = Category::create(
            AggregateRootId::generate(),
            Name::withName($command->getName())
        );
        $this->categoryStoreRepository->save($category);
    }
}
