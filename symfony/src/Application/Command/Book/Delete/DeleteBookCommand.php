<?php

declare(strict_types=1);

namespace App\Application\Command\Book\Delete;

class DeleteBookCommand
{
    /**
     * @var string
     */
    private $id;

    /**
     * DeleteBookCommand constructor.
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
