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

namespace Prooph\Snapshotter;

use Prooph\EventSourcing\Aggregate\AggregateRepository;
use Prooph\EventSourcing\Aggregate\AggregateTranslator;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\Projection\ReadModel;
use Prooph\EventStore\Util\Assertion;
use Prooph\SnapshotStore\Snapshot;
use Prooph\SnapshotStore\SnapshotStore;

final class SnapshotReadModel implements ReadModel
{
    /**
     * @var AggregateRepository
     */
    private $aggregateRepository;

    /**
     * @var AggregateTranslator
     */
    private $aggregateTranslator;

    /**
     * @var array
     */
    private $aggregateCache = [];

    /**
     * @var SnapshotStore
     */
    private $snapshotStore;

    /**
     * @var string[]
     */
    private $aggregateTypes;

    public function __construct(
        AggregateRepository $aggregateRepository,
        AggregateTranslator $aggregateTranslator,
        SnapshotStore $snapshotStore,
        array $aggregateTypes
    ) {
        Assertion::allString($aggregateTypes);
        Assertion::allNotEmpty($aggregateTypes);

        $this->aggregateRepository = $aggregateRepository;
        $this->aggregateTranslator = $aggregateTranslator;
        $this->aggregateTypes = $aggregateTypes;
        $this->snapshotStore = $snapshotStore;
    }

    public function stack(string $operation, ...$events): void
    {
        $event = $events[0];

        if (! $event instanceof AggregateChanged) {
            throw new \RuntimeException(get_class($this) . ' can only handle events of type ' . AggregateChanged::class);
        }

        $this->aggregateCache[] = $event->aggregateId();
    }

    public function persist(): void
    {
        foreach (array_unique($this->aggregateCache) as $aggregateId) {
            $aggregateRoot = $this->aggregateRepository->getAggregateRoot($aggregateId);
            $this->snapshotStore->save(new Snapshot(
                (string) AggregateType::fromAggregateRoot($aggregateRoot),
                $aggregateId,
                $aggregateRoot,
                $this->aggregateTranslator->extractAggregateVersion($aggregateRoot),
                new \DateTimeImmutable('now', new \DateTimeZone('UTC'))
            ));
        }

        $this->aggregateRepository->clearIdentityMap();
        $this->aggregateCache = [];
    }

    public function init(): void
    {
        // do nothing
    }

    public function isInitialized(): bool
    {
        return true;
    }

    public function reset(): void
    {
        foreach ($this->aggregateTypes as $aggregateType) {
            $this->snapshotStore->removeAll($aggregateType);
        }
    }

    public function delete(): void
    {
        foreach ($this->aggregateTypes as $aggregateType) {
            $this->snapshotStore->removeAll($aggregateType);
        }
    }
}
