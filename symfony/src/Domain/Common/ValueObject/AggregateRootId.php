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
    private function __construct(string $id)
    {
        Assertion::uuid($id, "This is not valid uuid");
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

    public static function withId(string $id): self
    {
        $id = new self($id);

        return $id;
    }
}
