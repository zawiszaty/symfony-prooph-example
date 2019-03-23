<?php

declare(strict_types=1);

namespace App\Infrastructure\Category\Projection;

use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Category\Query\Repository\MysqlCategoryRepository;
use Doctrine\DBAL\Connection;
use Prooph\EventStore\Projection\AbstractReadModel;

class CategoryReadModel extends AbstractReadModel
{
    /**
     * @var MysqlCategoryRepository
     */
    private $categoryRepository;
    /**
     * @var Connection
     */
    private $connection;

    public function __construct(MysqlCategoryRepository $categoryRepository, Connection $connection)
    {
        $this->categoryRepository = $categoryRepository;
        $this->connection = $connection;
    }

    public function init(): void
    {
        return;
    }

    public function isInitialized(): bool
    {
        return true;
    }

    public function reset(): void
    {
        return;
    }

    public function delete(): void
    {
        return;
    }

    protected function insert(array $categoryView)
    {
        $categoryView = new CategoryView(
            $categoryView['id'],
            $categoryView['name']
        );
        $this->categoryRepository->add($categoryView);
    }

    protected function changeName(array $data)
    {
        /** @var CategoryView $categoryView */
        $categoryView = $this->categoryRepository->find($data['id']);
        $categoryView->changeName($data['name']);
        $this->categoryRepository->apply();
    }

    protected function deleteCategory(array $data)
    {
        $this->categoryRepository->delete($data['id']);
    }
}
