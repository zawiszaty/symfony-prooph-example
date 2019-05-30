<?php

declare(strict_types=1);

namespace App\Domain\Common\ValueObject;

use App\Domain\Common\Adapter\UuidAdapter;
use Assert\Assertion;

class AggregateRootId
{
    /**
     * @var string
     */
    private $id;

    /**
     * AggregateRootId constructor.
     *
     * @param string $id
     */
    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public static function generate()
    {
        $id = new self(UuidAdapter::generate()->toString());

        return $id;
    }

    public function toString(): string
    {
        return $this->id;
    }

    public static function fromString(string $id): self
    {
        Assertion::uuid($id);
        $id = new self($id);

        return $id;
    }
}
