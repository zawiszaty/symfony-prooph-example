<?php

declare(strict_types=1);

namespace App\Application\Command\Category\Delete;

class DeleteCategoryCommand
{
    /**
     * @var string
     */
    private $id;

    /**
     * DeleteCategoryCommand constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }
}
