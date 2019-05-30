<?php

declare(strict_types=1);

namespace App\Domain\Common\Adapter;

use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Exception\InvalidUuidStringException;
use Ramsey\Uuid\Uuid;

class UuidAdapterTest extends TestCase
{
    public function test_from_string()
    {
        $adapter = UuidAdapter::fromString(Uuid::uuid4()->toString());
        $this->assertTrue(Uuid::isValid($adapter->toString()));
    }

    public function test_from_string_throw_exception_when_is_not_a_uuid()
    {
        $this->expectException(InvalidUuidStringException::class);
        UuidAdapter::fromString('123');
    }

    public function test_generate()
    {
        $adapter = UuidAdapter::generate();
        $this->assertTrue(Uuid::isValid($adapter->toString()));
    }
}
