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

use Assert\Assertion;
use DateTimeImmutable;

final class Snapshot
{
    /**
     * @var string
     */
    private $aggregateType;

    /**
     * @var string
     */
    private $aggregateId;

    /**
     * @var object|array
     */
    private $aggregateRoot;

    /**
     * @var int
     */
    private $lastVersion;

    /**
     * @var DateTimeImmutable
     */
    private $createdAt;

    public function __construct(
        string $aggregateType,
        string $aggregateId,
        $aggregateRoot,
        int $lastVersion,
        DateTimeImmutable $createdAt
    ) {
        Assertion::minLength($aggregateType, 1);
        Assertion::minLength($aggregateId, 1);
        Assertion::min($lastVersion, 1);

        $this->aggregateType = $aggregateType;
        $this->aggregateId = $aggregateId;
        $this->aggregateRoot = $aggregateRoot;
        $this->lastVersion = $lastVersion;
        $this->createdAt = $createdAt;
    }

    public function aggregateType(): string
    {
        return $this->aggregateType;
    }

    public function aggregateId(): string
    {
        return $this->aggregateId;
    }

    public function aggregateRoot()
    {
        return $this->aggregateRoot;
    }

    public function lastVersion(): int
    {
        return $this->lastVersion;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
