# Snapshotter

Snapshot tool for the prooph event-store. Take aggregate snapshots with ease.

## Installation

```
composer require prooph/snapshotter
```

## Snapshots via Projection

There are two projections shipped with this package:

1) CategorySnapshotProjection
2) StreamSnapshotProjection

## CategorySnapshotProjection

Use this one, if you are using one stream per aggregate, so when you have two users with ids `123` and `234` the event
streams are named `user-123` and `user-234`, in this case you need to create the snapshots by querying the category user.

## StreamSnapshotProjection

Use this one, if you are using one stream for all aggregates, so when you have two users with ids `123` and `234` the event
stream is simply `user` for both of them, in this case you need to create the snapshots by querying the stream user.

## Usage

You need to create a simple script, that might look similar to this and run it in background.
With the help of docker-containers or supervisord you can keep the script alive, if it dies.

```php
<?php

$container = include 'container.php';

$projectionManager = $container->get(\Prooph\EventStore\Projection\ProjectionManager::class);

$projection = $projectionManager->createReadModelProjection(
    'user-snapshots',
    new \Prooph\Snapshotter\SnapshotReadModel(
        $container->get('user_repository'),
        new \Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator(),
        $container->get(\Prooph\SnapshotStore\SnapshotStore::class),
        [
          'user',
        ]
    )
);

$categorySnapshotProjection = new \Prooph\Snapshotter\CategorySnapshotProjection($projection, 'user');
$categorySnapshotProjection();

// or

$streamSnapshotProjection = new \Prooph\Snapshotter\StreamSnapshotProjection($projection, 'user');
$streamSnapshotProjection();
```
