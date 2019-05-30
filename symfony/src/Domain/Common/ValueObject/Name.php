<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

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

    public static function withName(string $name): self
    {
        $name = new self($name);

        return $name;
    }

    public function toString(): string
    {
        return $this->name;
    }
}
