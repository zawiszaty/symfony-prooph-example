<?php

declare(strict_types=1);

namespace Tests\Application\Author;

use App\Application\Command\Author\ChangeName\ChangeAuthorNameCommand;
use App\Application\Command\Author\Create\CreateAuthorCommand;
use App\Domain\Category\Exception\SameNameException;
use Tests\TestCase;

class AuthorChangeNameTest extends TestCase
{
    public function test_author_it_change_name()
    {
        $command = new CreateAuthorCommand('test');
        $this->commandBus->handle($command);
        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = $this->container->get('doctrine')->getManager();
        $author = $manager->getRepository(\App\Infrastructure\Author\Query\Projections\AuthorView::class)->findOneBy(['name' => 'test']);
        $command = new ChangeAuthorNameCommand($author->getId(), 'test2');
        $this->commandBus->handle($command);
        $author = $manager->getRepository(\App\Infrastructure\Author\Query\Projections\AuthorView::class)->find($author->getId());
        $this->assertSame($author->getName(), 'test2');
    }

    public function test_author_it_not_change_same_name()
    {
        $this->expectException(SameNameException::class);
        $command = new CreateAuthorCommand('test');
        $this->commandBus->handle($command);
        /** @var \Doctrine\ORM\EntityManager $manager */
        $manager = $this->container->get('doctrine')->getManager();
        $author = $manager->getRepository(\App\Infrastructure\Author\Query\Projections\AuthorView::class)->findOneBy(['name' => 'test']);
        $command = new ChangeAuthorNameCommand($author->getId(), 'test');
        $this->commandBus->handle($command);
    }
}
