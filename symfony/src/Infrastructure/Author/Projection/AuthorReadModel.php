<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Projection;

use App\Domain\Author\AuthorRepository;
use App\Infrastructure\Author\Query\Projections\AuthorView;
use Doctrine\DBAL\Connection;
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

    public function insert(array $data)
    {
        $author = new AuthorView(
            $data['id'],
            $data['name']
        );
        $this->authorRepository->add($author);
    }

    public function changeName(array $data)
    {
        $author = $this->authorRepository->find($data['id']);
        $author->changeName($data['name']);
        $this->authorRepository->apply();
    }

    public function deleteAuthor(array $data)
    {
        $this->authorRepository->delete($data['id']);
    }
}
