<?php

declare(strict_types=1);

namespace App\Application\Command\Author\Delete;

class DeleteAuthorCommand
{
    /**
     * @var string
     */
    private $id;

    /**
     * DeleteAuthorCommand constructor.
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
