<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Query\Projections;

class AuthorView
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
     * AuthorView constructor.
     *
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
}
