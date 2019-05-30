<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Projection;

use App\Domain\Book\Event\BookWasCreated;
use App\Domain\Book\Event\BookWasDeleted;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Infrastructure\Author\Query\Repository\MysqlAuthorRepository;
use App\Infrastructure\Book\Query\Projections\BookMysqlRepository;
use App\Infrastructure\Book\Query\Projections\BookView;
use App\Infrastructure\Category\Query\Repository\MysqlCategoryRepository;
use Doctrine\DBAL\Connection;
use Prooph\EventSourcing\AggregateChanged;
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

    public function __invoke(AggregateChanged $event)
    {
        if ($event instanceof BookWasCreated) {
            $this->insert($event);
        } elseif ($event instanceof BookWasDeleted) {
            $this->deleteBook($event);
        }
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

    protected function insert(BookWasCreated $bookWasCreated): void
    {
        $category = $this->categoryRepository->oneByUuid(AggregateRootId::withId($bookWasCreated->getCategory()->toString()));
        $author = $this->authorRepository->oneByUuid(AggregateRootId::withId($bookWasCreated->getAuthor()->toString()));
        $bookView = new BookView(
            $bookWasCreated->getId()->toString(),
            $bookWasCreated->getName()->toString(),
            $bookWasCreated->getDescription()->toString(),
            $category,
            $author
        );
        $this->bookMysqlRepository->add($bookView);
    }

    public function deleteBook(BookWasDeleted $bookWasDeleted): void
    {
        $this->bookMysqlRepository->delete($bookWasDeleted->getId()->toString());
    }
}
