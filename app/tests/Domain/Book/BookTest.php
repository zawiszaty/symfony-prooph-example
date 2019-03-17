<?php

namespace Tests\Domain\Book;

use App\Domain\Book\Book;
use App\Domain\Book\Event\BookNameWasChanged;
use App\Domain\Book\Event\BookWasCreated;
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
}