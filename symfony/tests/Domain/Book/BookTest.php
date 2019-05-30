<?php

declare(strict_types=1);

namespace Tests\Domain\Book;

use App\Domain\Book\Book;
use App\Domain\Book\Event\BookDescriptionWasChanged;
use App\Domain\Book\Event\BookNameWasChanged;
use App\Domain\Book\Event\BookWasCreated;
use App\Domain\Book\Event\BookWasDeleted;
use App\Domain\Book\Exception\SameDescryptionException;
use App\Domain\Book\ValueObject\Description;
use App\Domain\Category\Exception\SameNameException;
use App\Domain\Common\Adapter\UuidAdapter;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use Tests\TestCase;

class BookTest extends TestCase
{
    public function test_book_it_create()
    {
        $category = UuidAdapter::generate();
        $author = UuidAdapter::generate();
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test'),
            $category,
            $author
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
            'category' => $category->toString(),
            'author' => $author->toString(),
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    public function test_book_it_change_name()
    {
        $category = UuidAdapter::generate();
        $author = UuidAdapter::generate();
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test'),
            $category,
            $author
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
            'category' => $category->toString(),
            'author' => $author->toString(),
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $book->changeName(Name::fromString('test2'));
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookNameWasChanged::class, $events[0]);
        $expectedPayload = [
            'name' => 'test2',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    public function test_book_it_change_same_name()
    {
        $this->expectException(SameNameException::class);
        $category = UuidAdapter::generate();
        $author = UuidAdapter::generate();
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test'),
            $category,
            $author
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
            'category' => $category->toString(),
            'author' => $author->toString(),
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $book->changeName(Name::fromString('test'));
    }

    public function test_book_it_change_same_description()
    {
        $this->expectException(SameDescryptionException::class);
        $category = UuidAdapter::generate();
        $author = UuidAdapter::generate();
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test'),
            $category,
            $author
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
            'category' => $category->toString(),
            'author' => $author->toString(),
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $book->changeDescription(Description::fromString('test'));
    }

    public function test_book_it_change_description()
    {
        $category = UuidAdapter::generate();
        $author = UuidAdapter::generate();
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test'),
            $category,
            $author
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
            'category' => $category->toString(),
            'author' => $author->toString(),
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
        $book->changeDescription(Description::fromString('test2'));
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookDescriptionWasChanged::class, $events[0]);
        $expectedPayload = [
            'description' => 'test2',
        ];
        $this->assertEquals($expectedPayload, $events[0]->payload());
    }

    public function test_book_it_delete()
    {
        $category = UuidAdapter::generate();
        $author = UuidAdapter::generate();
        $book = Book::create(
            AggregateRootId::generate(),
            Name::fromString('test'),
            Description::fromString('test'),
            $category,
            $author
        );
        $this->assertInstanceOf(Book::class, $book);
        $events = $this->popRecordedEvent($book);
        $this->assertEquals(1, \count($events));
        $this->assertInstanceOf(BookWasCreated::class, $events[0]);
        $expectedPayload = [
            'name' => 'test',
            'description' => 'test',
            'category' => $category->toString(),
            'author' => $author->toString(),
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
