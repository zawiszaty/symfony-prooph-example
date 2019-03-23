<?php

declare(strict_types=1);

namespace App\Infrastructure\Category\Projection;

use App\Domain\Category\Events\CategoryNameWasChanged;
use App\Domain\Category\Events\CategoryWasCreated;
use App\Domain\Category\Events\CategoryWasDeleted;
use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\EventStore\Projection\ReadModelProjector;

/**
 * @method readModel()
 */
class CategoryProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->when([
                CategoryWasCreated::class => function ($state, CategoryWasCreated $event) {
                    /** @var CategoryReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('insert', [
                        'id' => $event->getId()->toString(),
                        'name' => $event->getName()->toString(),
                    ]);
                },
                CategoryNameWasChanged::class => function ($state, CategoryNameWasChanged $event) {
                    /** @var CategoryReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('changeName', [
                        'id' => $event->getId()->toString(),
                        'name' => $event->getName()->toString(),
                    ]);
                },
                CategoryWasDeleted::class => function ($state, CategoryWasDeleted $event) {
                    /** @var CategoryReadModel $readModel */
                    $readModel = $this->readModel();
                    $readModel->stack('deleteCategory', [
                        'id' => $event->getId()->toString(),
                    ]);
                },
            ]);

        return $projector;
    }
}
