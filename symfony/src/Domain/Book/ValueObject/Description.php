<?php

declare(strict_types=1);

namespace App\Domain\Book\ValueObject;

use App\Domain\Book\Exception\SameDescryptionException;

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
    public function __construct(string $description)
    {
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

    public function changeDescription(string $description)
    {
        if ($this->description === $description) {
            throw new SameDescryptionException();
        }
        $this->description = $description;
    }
}
