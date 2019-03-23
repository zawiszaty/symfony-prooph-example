<?php

declare(strict_types=1);

namespace Tests\Application\Command\Author;

use App\Application\Command\Author\Create\CreateAuthorCommand;
use App\Infrastructure\Common\CommandHandler\CommandBus;
use Tests\TestCase;

class CreateAuthorHandlerTest extends TestCase
{
    /**
     * @var CommandBus|null
     */
    private $commandBus;

    protected function setUp(): void
    {
        parent::setUp();
        $this->commandBus = $this->container->get('App\Infrastructure\Common\CommandHandler\CommandBus');
    }

    public function test_it_handle()
    {
        $command = new CreateAuthorCommand('test');
        $this->commandBus->handle($command);
        $author = $this->getAuthor('test');
        $this->assertSame($author->getName(), 'test');
    }
}
