<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Projection;

use App\Infrastructure\Book\Query\Projections\BookMysqlRepository;
use App\Infrastructure\Book\Query\Projections\BookView;
use Prooph\EventStore\Projection\AbstractReadModel;

class BookReadModel extends AbstractReadModel
{
    /**
     * @var BookMysqlRepository
     */
    private $bookMysqlRepository;

    public function __construct(BookMysqlRepository $bookMysqlRepository)
    {
        $this->bookMysqlRepository = $bookMysqlRepository;
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

    protected function insert(array $bookView)
    {
        $bookView = new BookView(
            $bookView['id'],
            $bookView['name'],
            $bookView['description'],
            $bookView['category'],
            $bookView['author']
        );
        $this->bookMysqlRepository->add($bookView);
    }
}
