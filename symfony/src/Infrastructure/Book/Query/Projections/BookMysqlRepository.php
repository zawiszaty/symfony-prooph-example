<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Query\Projections;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Common\Query\MysqlRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @method stack(string $string, array $array)
 */
class BookMysqlRepository extends MysqlRepository
{
    public function add(BookView $postView): void
    {
        $this->register($postView);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function oneByUuid(AggregateRootId $id)
    {
        $qb = $this->repository
            ->createQueryBuilder('book')
            ->where('book.id = :id')
            ->setParameter('id', $id->toString());

        return $this->oneOrException($qb);
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
        $this->class = BookView::class;
        parent::__construct($entityManager);
    }
}
