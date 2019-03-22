<?php

declare(strict_types=1);

namespace App\Infrastructure\Category\Projection;

use App\Domain\Category\Events\CategoryWasCreated;
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
            ])->run();
    }
}
