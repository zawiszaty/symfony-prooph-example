<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Projection;

use App\Domain\Book\Event\BookWasCreated;
use App\Infrastructure\Book\Query\Projections\BookMysqlRepository;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

/**
 * @method readModel()
 */
class BookProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when([
                BookWasCreated::class => function ($state, BookWasCreated $event) {
                    /** @var BookMysqlRepository $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('insert', [
                        'id' => $event->getId()->toString(),
                        'name' => $event->getName()->toString(),
                        'description' => $event->getDescription()->toString(),
                        'author' => $event->getAuthor(),
                        'category' => $event->getCategory(),
                    ]);
                },
            ]);

        return $projector;
    }
}
