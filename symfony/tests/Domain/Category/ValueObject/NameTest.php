<?php

declare(strict_types=1);

namespace App\Domain\Category\ValueObject;

use App\Domain\Common\ValueObject\Name;
use Tests\TestCase;

class NameTest extends TestCase
{
    /**
     * @var Name
     */
    private $name;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->name = Name::fromString('test');
    }

    public function test_it_return_correct()
    {
        $this->assertSame($this->name->toString(), 'test');
    }
}
