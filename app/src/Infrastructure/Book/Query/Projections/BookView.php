<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Query\Projections;

use App\Infrastructure\Author\Query\Projections\AuthorView;
use App\Infrastructure\Category\Query\Projections\CategoryView;

class BookView
{
    /**
     * @var string
     */
    private $id;
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var CategoryView
     */
    private $category;

    /**
     * @var AuthorView
     */
    private $author;

    /**
     * BookView constructor.
     *
     * @param string       $id
     * @param string       $name
     * @param string       $description
     * @param CategoryView $category
     * @param AuthorView   $author
     */
    public function __construct(string $id, string $name, string $description, CategoryView $category, AuthorView $author)
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->category = $category;
        $this->author = $author;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function changeName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return CategoryView
     */
    public function getCategory(): CategoryView
    {
        return $this->category;
    }

    /**
     * @return AuthorView
     */
    public function getAuthor(): AuthorView
    {
        return $this->author;
    }
}
