<?php

declare(strict_types=1);

namespace Tests\Domain\Book\ValueObject;

use App\Domain\Book\Exception\SameDescryptionException;
use App\Domain\Book\ValueObject\Description;
use Assert\InvalidArgumentException;
use Tests\TestCase;

class DescriptionTest extends TestCase
{
    /**
     * @var Description
     */
    protected $description;

    protected function setUp(): void
    {
        parent::setUp();
        $this->description = Description::withDescription('test');
    }

    public function test_it_to_string()
    {
        $this->assertSame($this->description->toString(), 'test');
    }

    public function test_it_throw_exception_when_get_empty_string()
    {
        $this->expectException(InvalidArgumentException::class);
        Description::withDescription("");
    }
}
