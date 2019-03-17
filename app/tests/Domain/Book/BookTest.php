<?php

namespace Tests\Domain\Book;

use App\Domain\Book\Book;
use App\Domain\Book\Event\BookWasCreated;
use App\Domain\Book\ValueObject\Description;
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
}