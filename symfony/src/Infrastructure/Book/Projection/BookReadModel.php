<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Projection;

use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Author\Query\Repository\MysqlAuthorRepository;
use App\Infrastructure\Book\Query\Projections\BookMysqlRepository;
use App\Infrastructure\Book\Query\Projections\BookView;
use App\Infrastructure\Category\Query\Repository\MysqlCategoryRepository;
use Doctrine\DBAL\Connection;
use Prooph\EventStore\Projection\AbstractReadModel;

class BookReadModel extends AbstractReadModel
{
    /**
     * @var BookMysqlRepository
     */
    private $bookMysqlRepository;

    /**
     * @var MysqlAuthorRepository
     */
    private $authorRepository;

    /**
     * @var MysqlCategoryRepository
     */
    private $categoryRepository;

    /**
     * @var \Doctrine\DBAL\Schema\Schema
     */
    private $schema;

    public function __construct(
        BookMysqlRepository $bookMysqlRepository,
        MysqlAuthorRepository $authorRepository,
        MysqlCategoryRepository $categoryRepository,
        Connection $schema
    ) {
        $this->bookMysqlRepository = $bookMysqlRepository;
        $this->authorRepository = $authorRepository;
        $this->categoryRepository = $categoryRepository;
        $this->schema = $schema->getSchemaManager()->createSchema();
    }

    public function init(): void
    {
        $this->schema->createTable('book');
    }

    public function isInitialized(): bool
    {
        return $this->schema->hasTable('book');
    }

    public function reset(): void
    {
        $this->schema->dropTable('book');
        $this->schema->createTable('book');
    }

    public function delete(): void
    {
        $this->schema->dropTable('book');
    }

    protected function insert(array $bookView): void
    {
        $category = $this->categoryRepository->oneByUuid(AggregateRootId::fromString($bookView['category']));
        $author = $this->authorRepository->oneByUuid(AggregateRootId::fromString($bookView['author']));
        $bookView = new BookView(
            $bookView['id'],
            $bookView['name'],
            $bookView['description'],
            $category,
            $author
        );
        $this->bookMysqlRepository->add($bookView);
    }

    public function deleteBook(array $data): void
    {
        $this->bookMysqlRepository->delete($data['id']);
    }
}
