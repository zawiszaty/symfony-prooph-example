<?php

declare(strict_types=1);

namespace App\Application\Command\Author\Create;

class CreateAuthorCommand
{
    /**
     * @var string
     */
    private $name;

    /**
     * CreateAuthorCommand constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
