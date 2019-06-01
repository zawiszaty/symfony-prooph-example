<?php

declare(strict_types=1);

namespace App\Domain\Category\ValueObject;

use App\Domain\Common\ValueObject\Name;
use Assert\InvalidArgumentException;
use Tests\TestCase;

class NameTest extends TestCase
{
    /**
     * @var Name
     */
    private $name;

    protected function setUp(): void
    {
        parent::setUp();
        $this->name = Name::withName('test');
    }

    public function test_it_return_correct()
    {
        $this->assertSame($this->name->toString(), 'test');
    }

    public function test_it_throw_exception_when_get_empty_string()
    {
        $this->expectException(InvalidArgumentException::class);
        Name::withName('');
    }
}
