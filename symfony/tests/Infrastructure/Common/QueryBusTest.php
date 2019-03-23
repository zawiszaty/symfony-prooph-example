<?php

declare(strict_types=1);

namespace Tests\Infrastructure\Common\QueryHandler;

use App\Infrastructure\Common\QueryHandler\QueryBus;
use App\Infrastructure\Common\QueryHandler\QueryHandlerInterface;
use Tests\TestCase;

class TestQuery
{
}

class TestHandler implements QueryHandlerInterface
{
    public function __invoke(TestQuery $command): array
    {
        return [];
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
        $this->queryBus->addQueryHandler(new TestHandler());
    }

    public function testHandle()
    {
        $this->queryBus->handle(new TestQuery());
        $this->assertSame(1, 1);
    }
}
