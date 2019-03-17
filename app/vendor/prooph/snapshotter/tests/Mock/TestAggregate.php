<?php
/**
 * This file is part of the prooph/snapshotter.
 * (c) 2015-2017 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2017 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ProophTest\Snapshotter\Mock;

use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventSourcing\AggregateRoot;

final class TestAggregate extends AggregateRoot
{
    const ID = 'my_only_aggregate';

    private $eventsCount = [];

    public function eventsCount(): array
    {
        return $this->eventsCount;
    }

    public function aggregateId(): string
    {
        return self::ID;
    }

    protected function apply(AggregateChanged $event): void
    {
        if (! isset($this->eventsCount[$event->uuid()->toString()])) {
            $this->eventsCount[$event->uuid()->toString()] = 0;
        }

        ++$this->eventsCount[$event->uuid()->toString()];
    }
}
