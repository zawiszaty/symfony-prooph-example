<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Projection;

use App\Domain\Author\AuthorRepository;
use App\Domain\Author\Events\AuthorNameWasChanged;
use App\Domain\Author\Events\AuthorWasCreated;
use App\Domain\Author\Events\AuthorWasDeleted;
use App\Infrastructure\Author\Query\Projections\AuthorView;
use Doctrine\DBAL\Connection;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\Projection\AbstractReadModel;

class AuthorReadModel extends AbstractReadModel
{
    /**
     * @var AuthorRepository
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
        switch (get_class($event)) {
            case AuthorWasCreated::class:
                /** @var AuthorWasCreated $event */
                $authorWasCreated = $event;
                $this->insert($authorWasCreated);
                break;
            case AuthorNameWasChanged::class:
                /** @var AuthorNameWasChanged $event */
                $authorNameWasChanged = $event;
                $this->changeName($authorNameWasChanged);
                break;
            case AuthorWasDeleted::class:
                /** @var AuthorWasDeleted $event */
                $authorWasDeleted = $event;
                $this->deleteAuthor($authorWasDeleted->getId()->toString());
                break;
        }
    }

    public function __construct(AuthorRepository $authorRepository, Connection $connection)
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
        $author = $this->authorRepository->find($authorNameWasChanged->getId()->toString());
        $author->changeName($authorNameWasChanged->getName()->toString());
        $this->authorRepository->apply();
    }

    public function deleteAuthor(string $id)
    {
        $this->authorRepository->delete($id);
    }
}
