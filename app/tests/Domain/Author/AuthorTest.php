<?php

namespace Tests\Domain\Author;

use App\Domain\Author\Author;
use App\Domain\Author\Events\AuthorWasCreated;
use App\Domain\Common\ValueObject\AggregateRootId;
use App\Domain\Common\ValueObject\Name;
use Tests\TestCase;

class AuthorTest extends TestCase
{
    function test_it_create()
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
}