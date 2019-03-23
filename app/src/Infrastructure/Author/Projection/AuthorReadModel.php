<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Projection;

use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Author\Query\Repository\MysqlAuthorRepository;
use Prooph\EventStore\Projection\AbstractReadModel;

class AuthorReadModel extends AbstractReadModel
{
    /**
     * @var MysqlAuthorRepository
     */
    private $authorRepository;

    public function __construct(MysqlAuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
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
