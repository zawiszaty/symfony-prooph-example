<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Query\Repository;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Common\Query\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

class MysqlAuthorRepository extends MysqlRepository
{
    public function add(AuthorView $postView): void
    {
        $this->register($postView);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(AggregateRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('author')
            ->where('author.id = :id')
            ->setParameter('id', $id->toString());

        return $this->oneOrException($qb);
    }

    public function find(string $id): AuthorView
    {
        /** @var AuthorView $author */
        $author = $this->repository->find($id);

        return $author;
    }

    public function delete(string $id)
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
}
