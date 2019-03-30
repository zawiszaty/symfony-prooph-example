<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use App\Domain\Category\Exception\SameNameException;

class Name
{
    /**
     * @var string
     */
    private $name;

    /**
     * Name constructor.
     *
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public static function fromString(string $name): self
    {
        $name = new self($name);

        return $name;
    }

    public function toString(): string
    {
        return $this->name;
    }

    public function changeName(string $name): self
    {
        if ($this->name === $name) {
            throw new SameNameException();
        }

        return new self($name);
    }
}
