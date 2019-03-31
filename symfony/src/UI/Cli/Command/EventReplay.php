<?php

declare(strict_types=1);

namespace App\UI\Cli\Command;

use App\Infrastructure\Author\Projection\AuthorReadModel;
use App\Infrastructure\Book\Projection\BookReadModel;
use App\Infrastructure\Category\Projection\CategoryReadModel;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Pdo\Projection\MySqlProjectionManager;
use Prooph\EventStore\Projection\ProjectionManager;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class EventReplay extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('event:replay:command')
            ->setDescription('Create event_stream.')
            ->setHelp('This command creates the event_stream');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var EventStore $eventStore */
        $eventStore = $this->getContainer()->get('app.event_store.default');
        /** @var \PDO $pdo */
        $pdo = $this->getContainer()->get('app.event_store.pdo_connection.mysql');
        /** @var AuthorReadModel $authorReadModel */
        $authorReadModel = $this->getContainer()->get('App\Infrastructure\Author\Projection\AuthorReadModel');
        /** @var CategoryReadModel $categoryReadModel */
        $categoryReadModel = $this->getContainer()->get('App\Infrastructure\Category\Projection\CategoryReadModel');
        /** @var BookReadModel $bookReadModel */
        $bookReadModel = $this->getContainer()->get('App\Infrastructure\Book\Projection\BookReadModel');
        /** @var ProjectionManager $projectionManager */
        $projectionManager = new MySqlProjectionManager($eventStore, $pdo);

        $projectionAuthor = $projectionManager->createProjection('author_projection');
        $projectionAuthor
            ->fromStream('event_stream')
            ->whenAny(
                function (array $state, AggregateChanged $event) use ($authorReadModel): array {
                    $authorReadModel($event);

                    return $state;
                }
            );
        $projectionAuthor->run(false);

        $projectionAuthor = $projectionManager->createProjection('category_projection');
        $projectionAuthor
            ->fromStream('event_stream')
            ->whenAny(
                function (array $state, AggregateChanged $event) use ($categoryReadModel): array {
                    $categoryReadModel($event);

                    return $state;
                }
            );
        $projectionAuthor->run(false);

        $projectionBook = $projectionManager->createProjection('book_projection');
        $projectionBook
            ->fromStream('event_stream')
            ->whenAny(
                function (array $state, AggregateChanged $event) use ($bookReadModel): array {
                    $bookReadModel($event);

                    return $state;
                }
            );
        $projectionBook->run(false);

        $projectionManager->resetProjection('author_projection');
        $projectionManager->resetProjection('category_projection');
        $projectionManager->resetProjection('book_projection');
        $output->writeln('<info>Event Replayed.</info>');
    }
}
