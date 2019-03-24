<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Query\Projections;

use App\Domain\Book\BookRepository;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Common\Query\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @method stack(string $string, array $array)
 */
class BookMysqlRepository extends MysqlRepository implements BookRepository
{
    public function add(BookView $postView): void
    {
        $this->register($postView);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(AggregateRootId $id): BookView
    {
        $qb = $this->repository
            ->createQueryBuilder('book')
            ->where('book.id = :id')
            ->setParameter('id', $id->toString());

        return $this->oneOrException($qb);
    }

    public function delete(string $id): void
    {
        /** @var object|null $post */
        $post = $this->repository->find($id);

        if (!$post) {
            throw new NotFoundHttpException();
        }
        $this->entityManager->remove($post);
        $this->entityManager->flush();
    }

    public function getAllByAuthorId(string $name): array
    {
        $data = $this->repository
            ->createQueryBuilder('book')
            ->where('book.author = :id')
            ->setParameter('id', $name)
            ->getQuery()
            ->getResult();

        return $data;
    }

    /**
     * MysqlUserReadModelRepository constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->class = BookView::class;
        parent::__construct($entityManager);
    }
}
