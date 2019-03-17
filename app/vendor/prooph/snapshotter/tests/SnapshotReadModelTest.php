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

namespace ProophTest\Snapshotter;

use PHPUnit\Framework\TestCase;
use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateTranslator;
use Prooph\SnapshotStore\SnapshotStore;
use Prooph\Snapshotter\SnapshotReadModel;

class SnapshotReadModelTest extends TestCase
{
    /**
     * @var SnapshotReadModel
     */
    private $snapshotReadModel;

    /**
     * @test
     */
    public function it_cannot_stack_unknown_event_types(): void
    {
        $this->expectException(\RuntimeException::class);

        $this->snapshotReadModel->stack('replay', 'invalid event');
    }

    /**
     * @test
     */
    public function it_handles_reset(): void
    {
        $snapshotStore = $this->prophesize(SnapshotStore::class);
        $snapshotStore->removeAll('ProophTest\EventSourcing\Mock\User')->shouldBeCalled();

        $snapshotReadModel = new SnapshotReadModel(
            $this->prophesize(AggregateRepository::class)->reveal(),
            $this->prophesize(AggregateTranslator::class)->reveal(),
            $snapshotStore->reveal(),
            ['ProophTest\EventSourcing\Mock\User']
        );

        $snapshotReadModel->reset();
    }

    /**
     * @test
     */
    public function it_handles_delete(): void
    {
        $snapshotStore = $this->prophesize(SnapshotStore::class);
        $snapshotStore->removeAll('ProophTest\EventSourcing\Mock\User')->shouldBeCalled();

        $snapshotReadModel = new SnapshotReadModel(
            $this->prophesize(AggregateRepository::class)->reveal(),
            $this->prophesize(AggregateTranslator::class)->reveal(),
            $snapshotStore->reveal(),
            ['ProophTest\EventSourcing\Mock\User']
        );

        $snapshotReadModel->delete();
    }

    protected function setUp(): void
    {
        $this->snapshotReadModel = new SnapshotReadModel(
            $this->prophesize(AggregateRepository::class)->reveal(),
            $this->prophesize(AggregateTranslator::class)->reveal(),
            $this->prophesize(SnapshotStore::class)->reveal(),
            ['ProophTest\EventSourcing\Mock\User']
        );
    }
}
