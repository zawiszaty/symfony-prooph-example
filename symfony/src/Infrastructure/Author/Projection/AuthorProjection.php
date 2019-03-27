<?php

declare(strict_types=1);

namespace App\Infrastructure\Author\Projection;

use Prooph\Bundle\EventStore\Projection\ReadModelProjection;
use Prooph\Common\Messaging\Message;
use Prooph\EventStore\Projection\ReadModelProjector;

/**
 * @method readModel()
 */
class AuthorProjection implements ReadModelProjection
{
    public function project(ReadModelProjector $projector): ReadModelProjector
    {
        $projector->fromStream('event_stream')
            ->whenAny(function ($state, Message $event) {
                $readModel = $this->readModel();
                $readModel($event);
            });

        return $projector;
    }
}
