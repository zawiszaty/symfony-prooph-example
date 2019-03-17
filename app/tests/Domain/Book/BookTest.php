<?php

namespace Tests\Domain\Book;

use App\Domain\Book\Book;
use App\Domain\Book\Event\BookDescriptionWasChanged;
use App\Domain\Book\Event\BookNameWasChanged;
use App\Domain\Book\Event\BookWasCreated;
use App\Domain\Book\Event\BookWasDeleted;
use App\Domain\Book\Exception\SameDescryptionException;
use App\Domain\Book\ValueObject\Description;
use App\Domain\Category\Exception\SameNameException;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use Tests\TestCase;

class BookTest extends TestCase
{
    function test_book_it_create()
    {
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test')
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    function test_book_it_change_name()
    {
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test')
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $book->changeName('test2');
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookNameWasChanged::class, $events[0]);
        $expectedPayload = [
            'name' => 'test2',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    function test_book_it_change_same_name()
    {
        $this->expectException(SameNameException::class);
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test')
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $book->changeName('test');
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookNameWasChanged::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    function test_book_it_change_same_description()
    {
        $this->expectException(SameDescryptionException::class);
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test')
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $book->changeDescription('test');
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookDescriptionWasChanged::class, $events[0]);
        $expectedPayload = [
            'description' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    function test_book_it_change_description()
    {
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test')
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $book->changeDescription('test2');
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookDescriptionWasChanged::class, $events[0]);
        $expectedPayload = [
            'description' => 'test2',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    function test_book_it_delete()
    {
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test')
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $book->delete();
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasDeleted::class, $events[0]);
        $expectedPayload = [];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }
}