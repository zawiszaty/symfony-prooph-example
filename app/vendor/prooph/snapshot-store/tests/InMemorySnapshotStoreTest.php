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

namespace ProophTest\SnapshotStore;

use PHPUnit\Framework\TestCase;
use Prooph\SnapshotStore\InMemorySnapshotStore;
use Prooph\SnapshotStore\Snapshot;

class InMemorySnapshotStoreTest extends TestCase
{
    /**
     * @test
     */
    public function it_saves_snapshots(): void
    {
        $now = new \DateTimeImmutable();

        $snapshot = new Snapshot(
            'foo',
            'some_id',
            [
                'some' => 'thing',
            ],
            1,
            $now
        );

        $snapshotStore = new InMemorySnapshotStore();
        $snapshotStore->save($snapshot);

        $this->assertSame($snapshot, $snapshotStore->get('foo', 'some_id'));

        $this->assertNull($snapshotStore->get('some', 'invalid'));
    }

    /**
     * @test
     */
    public function it_saves_multiple_snapshots_and_removes_them(): void
    {
        $now = new \DateTimeImmutable();

        $snapshot1 = new Snapshot(
            'foo',
            'some_id',
            [
                'some' => 'thing',
            ],
            1,
            $now
        );

        $snapshot2 = new Snapshot(
            'bar',
            'some_other_id',
            [
                'some' => 'other_thing',
            ],
            1,
            $now
        );

        $snapshot3 = new Snapshot(
            'bar',
            'some_other_id_too',
            [
                'some' => 'other_thing_too',
            ],
            1,
            $now
        );

        $snapshotStore = new InMemorySnapshotStore();
        $snapshotStore->save($snapshot1, $snapshot2, $snapshot3);

        $this->assertSame($snapshot1, $snapshotStore->get('foo', 'some_id'));
        $this->assertSame($snapshot2, $snapshotStore->get('bar', 'some_other_id'));
        $this->assertSame($snapshot3, $snapshotStore->get('bar', 'some_other_id_too'));

        $snapshotStore->removeAll('bar');

        $this->assertSame($snapshot1, $snapshotStore->get('foo', 'some_id'));
        $this->assertNull($snapshotStore->get('bar', 'some_other_id'));
        $this->assertNull($snapshotStore->get('bar', 'some_other_id_too'));
    }
}
