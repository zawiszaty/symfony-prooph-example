<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use Assert\Assertion;

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
    private function __construct(string $name)
    {
        Assertion::notEmpty($name,"Name can' be blank");
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
