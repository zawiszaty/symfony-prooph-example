<?php

declare(strict_types=1);

namespace App\Infrastructure\Category\Query\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Category\Query\CategoryRepository;
use App\Infrastructure\Category\Query\Projections\CategoryView;
use App\Infrastructure\Common\Query\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method stack(string $string, array $array)
 */
class MysqlCategoryRepository extends MysqlRepository implements CategoryRepository
{
    public function add(CategoryView $postView): void
    {
        $this->register($postView);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(AggregateRootId $id): CategoryView
    {
        $qb = $this->repository
            ->createQueryBuilder('category')
            ->where('category.id = :id')
            ->setParameter('id', $id->toString());

        return $this->oneOrException($qb);
    }

    public function delete(string $id): void
    {
        /** @var object $post */
        $post = $this->repository->find($id);
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    /**
     * MysqlUserReadModelRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = CategoryView::class;
        parent::__construct($entityManager);
    }

    public function find(string $id): ?CategoryView
    {
        /** @var CategoryView $category */
        $category = $this->repository->find($id);

        return $category;
    }

    public function findOneBy(array $query): ?CategoryView
    {
        /** @var CategoryView|null $category */
        $category = $this->repository->findOneBy($query);

        return $category;
    }
}
