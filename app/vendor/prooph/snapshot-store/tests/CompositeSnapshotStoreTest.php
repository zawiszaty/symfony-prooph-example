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
use Prooph\SnapshotStore\CompositeSnapshotStore;
use Prooph\SnapshotStore\InMemorySnapshotStore;
use Prooph\SnapshotStore\Snapshot;
use Prooph\SnapshotStore\SnapshotStore;

class CompositeSnapshotStoreTest extends TestCase
{
    /**
     * @test
     */
    public function it_saves_snapshots_in_all_stores(): void
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

        $snapshotStore1 = new InMemorySnapshotStore();
        $snapshotStore2 = new InMemorySnapshotStore();
        $snapshotStore = new CompositeSnapshotStore($snapshotStore1, $snapshotStore2);

        $snapshotStore->save($snapshot);

        $this->assertSame($snapshot, $snapshotStore->get('foo', 'some_id'));
        $this->assertSame($snapshot, $snapshotStore1->get('foo', 'some_id'));
        $this->assertSame($snapshot, $snapshotStore2->get('foo', 'some_id'));

        $this->assertNull($snapshotStore->get('some', 'invalid'));
        $this->assertNull($snapshotStore1->get('some', 'invalid'));
        $this->assertNull($snapshotStore2->get('some', 'invalid'));
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

        $snapshotStore1 = new InMemorySnapshotStore();
        $snapshotStore2 = new InMemorySnapshotStore();
        $snapshotStore = new CompositeSnapshotStore($snapshotStore1, $snapshotStore2);

        $snapshotStore->save($snapshot1, $snapshot2, $snapshot3);

        $this->assertSame($snapshot1, $snapshotStore->get('foo', 'some_id'));
        $this->assertSame($snapshot2, $snapshotStore->get('bar', 'some_other_id'));
        $this->assertSame($snapshot3, $snapshotStore->get('bar', 'some_other_id_too'));
        $this->assertSame($snapshot1, $snapshotStore1->get('foo', 'some_id'));
        $this->assertSame($snapshot2, $snapshotStore1->get('bar', 'some_other_id'));
        $this->assertSame($snapshot3, $snapshotStore1->get('bar', 'some_other_id_too'));
        $this->assertSame($snapshot1, $snapshotStore2->get('foo', 'some_id'));
        $this->assertSame($snapshot2, $snapshotStore2->get('bar', 'some_other_id'));
        $this->assertSame($snapshot3, $snapshotStore2->get('bar', 'some_other_id_too'));

        $snapshotStore->removeAll('bar');

        $this->assertSame($snapshot1, $snapshotStore->get('foo', 'some_id'));
        $this->assertNull($snapshotStore->get('bar', 'some_other_id'));
        $this->assertNull($snapshotStore->get('bar', 'some_other_id_too'));
        $this->assertSame($snapshot1, $snapshotStore1->get('foo', 'some_id'));
        $this->assertNull($snapshotStore1->get('bar', 'some_other_id'));
        $this->assertNull($snapshotStore1->get('bar', 'some_other_id_too'));
        $this->assertSame($snapshot1, $snapshotStore2->get('foo', 'some_id'));
        $this->assertNull($snapshotStore2->get('bar', 'some_other_id'));
        $this->assertNull($snapshotStore2->get('bar', 'some_other_id_too'));
    }

    /**
     * @test
     */
    public function it_returns_result_from_first_store_with_content(): void
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

        $snapshotStore1 = $this->prophesize(SnapshotStore::class);
        $snapshotStore1->get('foo', 'some_id')->willReturn(null)->shouldBeCalled();

        $snapshotStore2 = $this->prophesize(SnapshotStore::class);
        $snapshotStore2->get('foo', 'some_id')->willReturn($snapshot)->shouldBeCalled();

        $snapshotStore3 = $this->prophesize(SnapshotStore::class);
        $snapshotStore3->get('foo', 'some_id')->shouldNotBeCalled();

        $snapshotStore = new CompositeSnapshotStore(
            $snapshotStore1->reveal(),
            $snapshotStore2->reveal(),
            $snapshotStore3->reveal()
        );

        $this->assertSame($snapshot, $snapshotStore->get('foo', 'some_id'));
    }
}
