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

interface SnapshotStore
{
    public function get(string $aggregateType, string $aggregateId): ?Snapshot;

    public function save(Snapshot ...$snapshots): void;

    public function removeAll(string $aggregateType): void;
}
