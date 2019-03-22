<?php

declare(strict_types=1);

namespace App\Application\Command\Category\ChangeName;

/**
 * Class ChangeCategoryNameCommand.
 */
class ChangeCategoryNameCommand
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
     * ChangeCategoryNameCommand constructor.
     *
     * @param string $id
     * @param string $name
     */
    public function __construct(string $id, string $name)
    {
        $this->id = $id;
        $this->name = $name;
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
    public function getId(): string
    {
        return $this->id;
    }
}
