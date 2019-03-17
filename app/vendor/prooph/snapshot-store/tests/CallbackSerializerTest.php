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
use Prooph\SnapshotStore\CallbackSerializer;
use Prooph\SnapshotStore\Serializer;

class CallbackSerializerTest extends TestCase
{
    /**
     * @test
     */
    public function it_implements_interface(): void
    {
        $serializer = new CallbackSerializer(null, null);

        $this->assertInstanceOf(Serializer::class, $serializer);
    }

    /**
     * @test
     */
    public function it_uses_serializer_by_default(): void
    {
        $serializer = new CallbackSerializer(null, null);
        $before = new \stdClass();
        $serialized = $serializer->serialize($before);
        $after = $serializer->unserialize($serialized);

        $this->assertEquals('O:8:"stdClass":0:{}', $serialized);
        $this->assertEquals($before, $after);
    }

    /**
     * @test
     */
    public function it_uses_default_if_only_one_callback_provided_instead_of_two(): void
    {
        $serializer = new CallbackSerializer(function ($data): string {
            return (string) ($data * 2);
        }, null);

        $before = new \stdClass();
        $serialized = $serializer->serialize($before);
        $after = $serializer->unserialize($serialized);

        $this->assertEquals('O:8:"stdClass":0:{}', $serialized);
        $this->assertEquals($before, $after);

        $serializer = new CallbackSerializer(null,
            function (string $data): string {
                return (string) ($data / 2);
            });

        $serialized = $serializer->serialize($before);
        $after = $serializer->unserialize($serialized);

        $this->assertEquals('O:8:"stdClass":0:{}', $serialized);
        $this->assertEquals($before, $after);
    }

    /**
     * @test
     */
    public function it_can_use_any_callback(): void
    {
        $serializer = new CallbackSerializer(function ($data): string {
            return (string) ($data * 2);
        }, function (string $data): string {
            return (string) ($data / 2);
        });

        $before = '10';
        $serialized = $serializer->serialize($before);
        $after = $serializer->unserialize($serialized);

        $this->assertEquals('20', $serialized);
        $this->assertEquals($before, $after);
    }
}
