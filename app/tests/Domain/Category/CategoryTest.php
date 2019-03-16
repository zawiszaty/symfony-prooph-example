<?php

declare(strict_types=1);

namespace App\Domain\Category;

use App\Domain\Category\Events\CategoryWasCreated;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Unit\TestCase;

class CategoryTest extends TestCase
{
    public function test_it_create()
    {
        $category = Category::create(
            AggregateRootId::generate(),
            Name::fromString('test')
        );
        $this->assertInstanceOf(Category::class, $category);
        $events = $this->popRecordedEvent($category);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(CategoryWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }
}
