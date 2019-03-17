<?php

declare(strict_types=1);

namespace Tests\Domain\Category;

use App\Domain\Category\Category;
use App\Domain\Category\Events\CategoryNameWasChanged;
use App\Domain\Category\Events\CategoryWasCreated;
use App\Domain\Category\Events\CategoryWasDeleted;
use App\Domain\Category\Exception\SameNameException;
use App\Domain\Category\ValueObject\Name;
use App\Domain\Common\ValueObject\AggregateRootId;
use Tests\TestCase;

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

    public function test_it_change_name()
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
        $category->changeName('test2');
        $events = $this->popRecordedEvent($category);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(CategoryNameWasChanged::class, $events[0]);
        $expectedPayload = [
            'name' => 'test2',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    public function test_it_change_to_same_name()
    {
        self::expectException(SameNameException::class);
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
        $category->changeName('test');
        $events = $this->popRecordedEvent($category);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(CategoryNameWasChanged::class, $events[0]);
        $expectedPayload = [
            'name' => 'test2',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    public function test_it_delete()
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
        $category->delete();
        $events = $this->popRecordedEvent($category);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(CategoryWasDeleted::class, $events[0]);
        $expectedPayload = [];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }
}
