<?php

declare(strict_types=1);

namespace App\Infrastructure\Book\Projection;

use App\Domain\Book\Event\BookWasCreated;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

class BookProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when([
                BookWasCreated::class => function ($state, BookWasCreated $event) {
                    /** @var BookReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('insert', [
                        'id' => $event->userId()->toString(),
                        'name' => $event->name()->toString(),
                        'email' => $event->emailAddress()->toString(),
                    ]);
                },
            ]);
    }
}
