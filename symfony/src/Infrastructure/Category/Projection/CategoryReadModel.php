<?php

declare(strict_types=1);

namespace App\Infrastructure\Category\Projection;

use App\Domain\Category\CategoryRepository;
use App\Domain\Category\Events\CategoryNameWasChanged;
use App\Domain\Category\Events\CategoryWasCreated;
use App\Domain\Category\Events\CategoryWasDeleted;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use Doctrine\DBAL\Connection;
use Prooph\EventStore\Projection\AbstractReadModel;

class CategoryReadModel extends AbstractReadModel
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var \Doctrine\DBAL\Schema\Schema
     */
    private $schema;

    public function __invoke($event): void
    {
        if ($event instanceof CategoryWasCreated) {
            $this->insert($event);
        } elseif ($event instanceof CategoryNameWasChanged) {
            $this->changeName($event);
        } elseif ($event instanceof CategoryWasDeleted) {
            $this->deleteCategory($event);
        }
    }

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository, Connection $connection)
    {
        $this->categoryRepository = $categoryRepository;
        $this->connection = $connection;
        $this->schema = $connection->getSchemaManager()->createSchema();
    }

    public function init(): void
    {
        $this->schema->createTable('category');
    }

    public function isInitialized(): bool
    {
        return $this->schema->hasTable('category');
    }

    public function reset(): void
    {
        $this->schema->dropTable('category');
        $this->schema->createTable('category');
    }

    public function delete(): void
    {
        $this->schema->dropTable('category');
    }

    protected function insert(CategoryWasCreated $categoryWasCreated)
    {
        $categoryView = new CategoryView(
            $categoryWasCreated->getId()->toString(),
            $categoryWasCreated->getName()->toString()
        );
        $this->categoryRepository->add($categoryView);
    }

    protected function changeName(CategoryNameWasChanged $categoryNameWasChanged)
    {
        /** @var CategoryView $categoryView */
        $categoryView = $this->categoryRepository->find($categoryNameWasChanged->getId()->toString());
        $categoryView->changeName($categoryNameWasChanged->getName()->toString());
        $this->categoryRepository->apply();
    }

    protected function deleteCategory(CategoryWasDeleted $categoryWasDeleted)
    {
        $this->categoryRepository->delete($categoryWasDeleted->getId()->toString());
    }
}
