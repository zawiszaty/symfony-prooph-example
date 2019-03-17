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
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Prooph\EventStore\InMemoryEventStore;
use Prooph\EventStore\Projection\InMemoryEventStoreReadModelProjector;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Prooph\SnapshotStore\InMemorySnapshotStore;
use Prooph\Snapshotter\SnapshotReadModel;
use Prooph\Snapshotter\StreamSnapshotProjection;
use ProophTest\EventSourcing\Mock\User;
use ProophTest\EventSourcing\Mock\UserNameChanged;
use ProophTest\Snapshotter\Mock\TestAggregate;

class StreamSnapshotProjectionTest extends TestCase
{
    /**
     * @test
     */
    public function it_takes_snapshots(): void
    {
        $user1 = User::nameNew('Aleks');
        $user1->changeName('Alex');

        $user2 = User::nameNew('Sasha');
        $user2->changeName('Sascha');

        $eventStore = new InMemoryEventStore();

        $eventStore->create(
            new Stream(
                new StreamName('user'),
                new \ArrayIterator()
            )
        );

        $snapshotStore = new InMemorySnapshotStore();
        $aggregateType = AggregateType::fromAggregateRoot($user1);
        $aggregateRepository = new AggregateRepository(
            $eventStore,
            $aggregateType,
            new AggregateTranslator(),
            $snapshotStore,
            new StreamName('user'),
            false
        );

        $aggregateRepository->saveAggregateRoot($user1);
        $aggregateRepository->saveAggregateRoot($user2);

        $streamSnapshotProjection = new StreamSnapshotProjection(
            new InMemoryEventStoreReadModelProjector(
                $eventStore,
                'user-snapshots',
                new SnapshotReadModel(
                    $aggregateRepository,
                    new AggregateTranslator(),
                    $snapshotStore,
                    [$aggregateType->toString()]
                ),
                5,
                1000,
                5
            ),
            'user'
        );

        $streamSnapshotProjection(false);

        $this->assertEquals($user1, $snapshotStore->get((string) $aggregateType, $user1->id())->aggregateRoot());
        $this->assertEquals($user2, $snapshotStore->get((string) $aggregateType, $user2->id())->aggregateRoot());
    }

    /**
     * @test
     */
    public function it_takes_snapshots_without_duplicates(): void
    {
        $events = [];
        for ($i = 0; $i < 10; $i++) {
            $events[] = UserNameChanged::occur('my_only_aggregate', ['username' => uniqid('name_')])
                ->withAddedMetadata('_aggregate_type', TestAggregate::class)
                ->withAddedMetadata('_aggregate_id', 'my_only_aggregate')
                ->withAddedMetadata('_aggregate_version', $i + 1);
        }

        $eventStore = new InMemoryEventStore();

        $eventStore->create(
            new Stream(
                new StreamName('user'),
                new \ArrayIterator($events)
            )
        );

        $snapshotStore = new InMemorySnapshotStore();
        $aggregateType = AggregateType::fromAggregateRootClass(TestAggregate::class);
        $aggregateRepository = new AggregateRepository(
            $eventStore,
            $aggregateType,
            new AggregateTranslator(),
            $snapshotStore,
            new StreamName('user'),
            false
        );

        $streamSnapshotProjection = new StreamSnapshotProjection(
            new InMemoryEventStoreReadModelProjector(
                $eventStore,
                'user-snapshots',
                new SnapshotReadModel(
                    $aggregateRepository,
                    new AggregateTranslator(),
                    $snapshotStore,
                    [$aggregateType->toString()]
                ),
                5,
                10,
                5
            ),
            'user'
        );

        $streamSnapshotProjection(false);

        $user = $aggregateRepository->getAggregateRoot('my_only_aggregate');

        foreach ($user->eventsCount() as $eventId => $count) {
            $this->assertSame(1, $count);
        }
    }
}
