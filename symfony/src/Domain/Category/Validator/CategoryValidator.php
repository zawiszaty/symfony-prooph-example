<?php

declare(strict_types=1);

namespace App\Domain\Category\Validator;

use App\Domain\Category\CategoryRepository;
use App\Domain\Category\Exception\CategoryNameExistException;
use App\Domain\Category\Exception\CategoryNotExistException;
use Assert\Assertion;

class CategoryValidator
{
    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @param string $string
     *
     * @throws CategoryNotExistException
     */
    public function exist(string $string): void
    {
        Assertion::uuid($string);
        if (!$this->categoryRepository->find($string)) {
            throw new CategoryNotExistException();
        }
    }

    /**
     * @param string $string
     *
     * @throws CategoryNotExistException
     */
    public function categoryNameExist(string $string): void
    {
        if ($this->categoryRepository->findOneBy(['name' => $string])) {
            throw new CategoryNameExistException();
        }
    }
}
