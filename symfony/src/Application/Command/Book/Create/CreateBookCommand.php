<?php

declare(strict_types=1);

namespace App\Application\Command\Book\Create;

class CreateBookCommand
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $category;

    /**
     * @var string
     */
    private $author;

    /**
     * CreateBookCommand constructor.
     *
     * @param string $name
     * @param string $description
     * @param string $category
     * @param string $author
     */
    public function __construct(string $name, string $description, string $category, string $author)
    {
        $this->name = $name;
        $this->description = $description;
        $this->category = $category;
        $this->author = $author;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return string
     */
    public function getCategory(): string
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }
}
