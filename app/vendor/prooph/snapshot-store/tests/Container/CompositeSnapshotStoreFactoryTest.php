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

namespace ProophTest\SnapshotStore\Container;

use PHPUnit\Framework\TestCase;
use Prooph\SnapshotStore\CompositeSnapshotStore;
use Prooph\SnapshotStore\Container\CompositeSnapshotStoreFactory;
use Prooph\SnapshotStore\SnapshotStore;
use Psr\Container\ContainerInterface;

class CompositeSnapshotStoreFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_composite_snapshot_store(): void
    {
        $config['prooph']['composite_snapshot_store']['default'] = [
            'snapshot_store_1',
            'snapshot_store_2',
        ];

        $snapshotStore1 = $this->prophesize(SnapshotStore::class);
        $snapshotStore2 = $this->prophesize(SnapshotStore::class);

        $container = $this->prophesize(ContainerInterface::class);

        $container->get('config')->willReturn($config)->shouldBeCalled();
        $container->get('snapshot_store_1')->willReturn($snapshotStore1->reveal())->shouldBeCalled();
        $container->get('snapshot_store_2')->willReturn($snapshotStore2->reveal())->shouldBeCalled();

        $factory = new CompositeSnapshotStoreFactory();
        $snapshotStore = $factory($container->reveal());

        $this->assertInstanceOf(CompositeSnapshotStore::class, $snapshotStore);
    }

    /**
     * @test
     */
    public function it_creates_composite_snapshot_store_via_call_static(): void
    {
        $config['prooph']['composite_snapshot_store']['default'] = [
            'snapshot_store_1',
            'snapshot_store_2',
        ];

        $snapshotStore1 = $this->prophesize(SnapshotStore::class);
        $snapshotStore2 = $this->prophesize(SnapshotStore::class);

        $container = $this->prophesize(ContainerInterface::class);

        $container->get('config')->willReturn($config)->shouldBeCalled();
        $container->get('snapshot_store_1')->willReturn($snapshotStore1->reveal())->shouldBeCalled();
        $container->get('snapshot_store_2')->willReturn($snapshotStore2->reveal())->shouldBeCalled();

        $serviceName = 'default';
        $snapshotStore = CompositeSnapshotStoreFactory::$serviceName($container->reveal());

        $this->assertInstanceOf(CompositeSnapshotStore::class, $snapshotStore);
    }

    /**
     * @test
     */
    public function it_throws_exception_when_invalid_container_given(): void
    {
        $this->expectException(\InvalidArgumentException::class);

        $eventStoreName = 'custom';
        CompositeSnapshotStoreFactory::$eventStoreName('invalid container');
    }
}
