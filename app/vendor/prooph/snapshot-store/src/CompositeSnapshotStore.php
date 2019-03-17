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

final class CompositeSnapshotStore implements SnapshotStore
{
    /**
     * @var SnapshotStore[]
     */
    private $snapshotStores;

    public function __construct(SnapshotStore ...$snapshotStores)
    {
        $this->snapshotStores = $snapshotStores;
    }

    public function get(string $aggregateType, string $aggregateId): ?Snapshot
    {
        foreach ($this->snapshotStores as $snapshotStore) {
            $snapshot = $snapshotStore->get($aggregateType, $aggregateId);

            if (null !== $snapshot) {
                return $snapshot;
            }
        }

        return null;
    }

    public function save(Snapshot ...$snapshots): void
    {
        foreach ($this->snapshotStores as $snapshotStore) {
            $snapshotStore->save(...$snapshots);
        }
    }

    public function removeAll(string $aggregateType): void
    {
        foreach ($this->snapshotStores as $snapshotStore) {
            $snapshotStore->removeAll($aggregateType);
        }
    }
}
