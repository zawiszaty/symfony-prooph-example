<?php

declare(strict_types=1);

namespace App\Domain\Common\Adapter;

interface UuidAdapterInterface
{
    public static function fromString(string $id): self;

    public function toString(): string;

    public static function generate(): self;
}
