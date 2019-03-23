<?php

declare(strict_types=1);

namespace App\Infrastructure\Category\Projection;

use App\Domain\Category\Events\CategoryNameWasChanged;
use App\Domain\Category\Events\CategoryWasCreated;
use App\Domain\Category\Events\CategoryWasDeleted;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Category\Query\Repository\MysqlCategoryRepository;
use Prooph\EventStore\Projection\AbstractReadModel;

class CategoryReadModel extends AbstractReadModel
{
    public function __invoke($event): void
    {
        if ($event instanceof CategoryWasCreated) {
            $this->insert($event->toArray());
        } elseif ($event instanceof CategoryNameWasChanged) {
            $this->changeName($event->toArray());
        } elseif ($event instanceof CategoryWasDeleted) {
            $this->deleteCategory($event->toArray());
        }
    }

    /**
     * @var MysqlCategoryRepository
     */
    private $categoryRepository;

    public function __construct(MysqlCategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
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
