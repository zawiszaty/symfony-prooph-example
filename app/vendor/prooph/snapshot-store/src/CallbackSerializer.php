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

final class CallbackSerializer implements Serializer
{
    /**
     * callable
     */
    private $serializeCallback = 'serialize';

    /**
     * callable
     */
    private $unserializeCallback = 'unserialize';

    public function __construct(?callable $serializeCallback, ?callable $unserializeCallback)
    {
        if (null !== $serializeCallback && null !== $unserializeCallback) {
            $this->serializeCallback = $serializeCallback;
            $this->unserializeCallback = $unserializeCallback;
        }
    }

    /**
     * @param object|array $data
     * @return string
     */
    public function serialize($data): string
    {
        return call_user_func($this->serializeCallback, $data);
    }

    /**
     * @param string $serialized
     * @return object|array
     */
    public function unserialize(string $serialized)
    {
        return call_user_func($this->unserializeCallback, $serialized);
    }
}
