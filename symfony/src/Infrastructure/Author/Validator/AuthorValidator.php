<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Validator;

use App\Domain\Author\Exception\AuthorNameFoundException;
use App\Domain\Author\Exception\AuthorNotFoundException;
use App\Infrastructure\Author\Query\AuthorRepository;

class AuthorValidator
{
    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    /**
     * @param string $id
     *
     * @throws AuthorNotFoundException
     */
    public function exist(string $id): void
    {
        if (!$this->authorRepository->find($id)) {
            throw new AuthorNotFoundException();
        }
    }

    public function authorNameExist(string $name)
    {
        if ($this->authorRepository->findOneBy(['name' => $name])) {
            throw new AuthorNameFoundException();
        }
    }
}
