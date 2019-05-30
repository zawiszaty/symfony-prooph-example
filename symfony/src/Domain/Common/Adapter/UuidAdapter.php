<?php

declare(strict_types=1);

namespace App\Domain\Common\Adapter;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UuidAdapter implements UuidAdapterInterface
{
    /**
     * @var UuidInterface
     */
    private $id;

    public static function fromString(string $id): UuidAdapterInterface
    {
        $self = new self();
        $self->id = Uuid::fromString($id);

        return $self;
    }

    public function toString(): string
    {
        return $this->id->toString();
    }

    public static function generate(): UuidAdapterInterface
    {
        $self = new self();
        $self->id = Uuid::uuid4();

        return $self;
    }
}
