<?php
/**
 * This file is part of the prooph/snapshot-store.
 * (c) 2017-2017 prooph software GmbH <contact@prooph.de>
 * (c) 2017-2017 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Prooph\SnapshotStore;

final class InMemorySnapshotStore implements SnapshotStore
{
    /**
     * @var array
     */
    private $map = [];

    public function get(string $aggregateType, string $aggregateId): ?Snapshot
    {
        if (! isset($this->map[$aggregateType][$aggregateId])) {
            return null;
        }

        return $this->map[$aggregateType][$aggregateId];
    }

    public function save(Snapshot ...$snapshots): void
    {
        foreach ($snapshots as $snapshot) {
            $this->map[$snapshot->aggregateType()][$snapshot->aggregateId()] = $snapshot;
        }
    }

    public function removeAll(string $aggregateType): void
    {
        unset($this->map[$aggregateType]);
    }
}
