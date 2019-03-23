<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Projection;

use App\Domain\Author\Events\AuthorNameWasChanged;
use App\Domain\Author\Events\AuthorWasCreated;
use App\Domain\Author\Events\AuthorWasDeleted;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

/**
 * @method readModel()
 */
class AuthorProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when([
                AuthorWasCreated::class => function ($state, AuthorWasCreated $event) {
                    /** @var AuthorReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('insert', [
                        'id' => $event->getId(),
                        'name' => $event->getName(),
                    ]);
                },
                AuthorNameWasChanged::class => function ($state, AuthorNameWasChanged $event) {
                    /** @var AuthorReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('changeName', [
                        'id' => $event->getId(),
                        'name' => $event->getName(),
                    ]);
                },
                AuthorWasDeleted::class => function ($state, AuthorWasDeleted $event) {
                    /** @var AuthorReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('deleteAuthor', [
                        'id' => $event->getId(),
                    ]);
                },
            ]);

        return $projector;
    }
}
