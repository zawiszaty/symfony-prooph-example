<?php

declare(strict_types=1);

namespace Tests\Domain\Author;

use App\Domain\Author\Author;
use App\Domain\Author\Events\AuthorNameWasChanged;
use App\Domain\Author\Events\AuthorWasCreated;
use App\Domain\Author\Events\AuthorWasDeleted;
use App\Domain\Category\Exception\SameNameException;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    public function test_it_create()
    {
        $author = Author::create(AggregateRootId::generate(), Name::fromString('test'));
        $this->assertInstanceOf(Author::class, $author);
        $events = $this->popRecordedEvent($author);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(AuthorWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    public function test_it_change_name()
    {
        $author = Author::create(AggregateRootId::generate(), Name::fromString('test'));
        $this->assertInstanceOf(Author::class, $author);
        $events = $this->popRecordedEvent($author);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(AuthorWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $author->changeName('test2');
        $events = $this->popRecordedEvent($author);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(AuthorNameWasChanged::class, $events[0]);
        $expectedPayload = [
            'name' => 'test2',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    public function test_it_change_same_name()
    {
        $this->expectException(SameNameException::class);
        $author = Author::create(AggregateRootId::generate(), Name::fromString('test'));
        $this->assertInstanceOf(Author::class, $author);
        $events = $this->popRecordedEvent($author);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(AuthorWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $author->changeName('test');
        $events = $this->popRecordedEvent($author);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(AuthorNameWasChanged::class, $events[0]);
        $expectedPayload = [
            'name' => 'test2',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    public function test_it_delete_author()
    {
        $author = Author::create(AggregateRootId::generate(), Name::fromString('test'));
        $this->assertInstanceOf(Author::class, $author);
        $events = $this->popRecordedEvent($author);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(AuthorWasCreated::class, $events[0]);
        $expectedPayload = [
             'name' => 'test',
         ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $author->delete();
        $events = $this->popRecordedEvent($author);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(AuthorWasDeleted::class, $events[0]);
        $expectedPayload = [];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }
}
