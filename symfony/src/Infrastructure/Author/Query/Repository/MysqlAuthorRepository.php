<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Query\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Author\Query\AuthorRepository;
use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Common\Query\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

class MysqlAuthorRepository extends MysqlRepository implements AuthorRepository
{
    public function add(AuthorView $authorView): void
    {
        $this->register($authorView);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(AggregateRootId $id): AuthorView
    {
        $qb = $this->repository
            ->createQueryBuilder('author')
            ->where('author.id = :id')
            ->setParameter('id', $id->toString());

        return $this->oneOrException($qb);
    }

    public function find(string $id): ?AuthorView
    {
        /** @var AuthorView $author */
        $author = $this->repository->find($id);

        return $author;
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
        $this->class = AuthorView::class;
        parent::__construct($entityManager);
    }

    public function findOneBy(array $query): ?AuthorView
    {
        /** @var AuthorView|null $author */
        $author = $this->repository->findOneBy($query);

        return $author;
    }
}
