<?php

namespace App\Infrastructure\Category\Query\Projections;

class CategoryView
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
     * CategoryView constructor.
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