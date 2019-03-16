<?php

declare(strict_types=1);

namespace App\Unit\Infrastructure\Common\QueryHandler;

use App\Infrastructure\Common\QueryHandler\QueryBus;
use App\Infrastructure\Common\QueryHandler\QueryHandlerInterface;
use App\Unit\TestCase;

class TestQuery
{
}

class TestHandler implements QueryHandlerInterface
{
    public function __invoke(TestQuery $command): void
    {
    }
}

class QueryBusTest extends TestCase
{
    /**
     * @var QueryBus|null
     */
    private $queryBus;

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->queryBus = $this->container->get('App\Infrastructure\Common\QueryHandler\QueryBus');
        $this->container->set('App\Unit\Infrastructure\Common\HandlerHandler\TestHandler', new TestHandler());
    }

    public function testHandle()
    {
        $this->queryBus->handle(new TestQuery());
        $this->assertSame(1, 1);
    }
}
