<?php

declare(strict_types=1);

namespace App\Application\Command\Category\Create;

/**
 * Class CreateCategoryCommand.
 */
class CreateCategoryCommand
{
    /**
     * @var string
     */
    private $name;

    /**
     * CreateCategoryCommand constructor.
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
