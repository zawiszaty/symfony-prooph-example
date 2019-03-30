<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Projection;

use App\Domain\Author\Author;
use App\Domain\Author\Events\AuthorNameWasChanged;
use App\Domain\Author\Events\AuthorWasCreated;
use App\Domain\Author\Events\AuthorWasDeleted;
use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Author\Query\Repository\MysqlAuthorRepository;
use Doctrine\DBAL\Connection;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\Projection\AbstractReadModel;

class AuthorReadModel extends AbstractReadModel
{
    /**
     * @var MysqlAuthorRepository
     */
    private $authorRepository;
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var \Doctrine\DBAL\Schema\Schema
     */
    private $schema;

    public function __invoke(AggregateChanged $event)
    {
        if ($event instanceof AuthorWasCreated) {
            $this->insert($event);
        } elseif ($event instanceof AuthorNameWasChanged) {
            $this->changeName($event);
        } elseif ($event instanceof AuthorWasDeleted) {
            $this->deleteAuthor($event->getId()->toString());
        }
    }

    public function __construct(MysqlAuthorRepository $authorRepository, Connection $connection)
    {
        $this->authorRepository = $authorRepository;
        $this->connection = $connection;
        $this->schema = $connection->getSchemaManager()->createSchema();
    }

    public function init(): void
    {
        $this->schema->createTable('author');
    }

    public function isInitialized(): bool
    {
        return $this->schema->hasTable('author');
    }

    public function reset(): void
    {
        $this->schema->dropTable('author');
        $this->schema->createTable('author');
    }

    public function delete(): void
    {
        $this->schema->dropTable('author');
    }

    public function insert(AuthorWasCreated $authorWasCreated)
    {
        $author = new AuthorView(
            $authorWasCreated->getId()->toString(),
            $authorWasCreated->getName()->toString()
        );
        $this->authorRepository->add($author);
    }

    public function changeName(AuthorNameWasChanged $authorNameWasChanged)
    {
        /** @var Author $author */
        $author = $this->authorRepository->find($authorNameWasChanged->getId()->toString());
        $author->changeName($authorNameWasChanged->getName()->toString());
        $this->authorRepository->apply();
    }

    public function deleteAuthor(string $id)
    {
        $this->authorRepository->delete($id);
    }
}
