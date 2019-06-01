<?php

declare(strict_types=1);

namespace App\Domain\Book\ValueObject;

use Assert\Assertion;

class Description
{
    /**
     * @var string
     */
    protected $description;

    /**
     * Description constructor.
     *
     * @param string $description
     */
    private function __construct(string $description)
    {
        Assertion::notEmpty($description,"Description can' be blank");
        $this->description = $description;
    }

    public static function withDescription(string $description): self
    {
        $self = new self($description);

        return $self;
    }

    public function toString(): string
    {
        return $this->description;
    }
}
