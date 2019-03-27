<?php

declare(strict_types=1);

namespace Tests\Application\Author;

use App\Application\Command\Author\Create\CreateAuthorCommand;
use App\Application\Command\Author\Delete\DeleteAuthorCommand;
use Tests\TestCase;

class AuthorDeleteTest extends TestCase
{
    public function test_author_it_delete()
    {
        $command = new CreateAuthorCommand('test');
        $this->commandBus->handle($command);
        $author = $this->manager->getRepository(\App\Infrastructure\Author\Query\Projections\AuthorView::class)->findOneBy(['name' => 'test']);
        $command = new DeleteAuthorCommand($author->getId());
        $this->commandBus->handle($command);
        $author = $this->manager->getRepository(\App\Infrastructure\Author\Query\Projections\AuthorView::class)->findOneBy(['name' => 'test']);
        $this->assertSame($author, null);
    }
}
