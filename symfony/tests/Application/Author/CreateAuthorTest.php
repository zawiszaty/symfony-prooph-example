<?php

declare(strict_types=1);

namespace Tests\Application\Author;

use App\Application\Command\Author\Create\CreateAuthorCommand;
use Tests\TestCase;

class CreateAuthorTest extends TestCase
{
    public function test_author_it_create()
    {
        $command = new CreateAuthorCommand('test');
        $this->commandBus->handle($command);
        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = $this->container->get('doctrine')->getManager();
        $author = $manager->getRepository(\App\Infrastructure\Author\Query\Projections\AuthorView::class)->findOneBy(['name' => 'test']);
        $this->assertSame($author->getName(), 'test');
    }
}
