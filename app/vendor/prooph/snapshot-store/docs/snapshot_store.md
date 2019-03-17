# Overview

Simple and lightweight snapshot store that works together with `prooph/event-sourcing` to speed up loading of aggregates.

## Installation

```bash
composer require prooph/snapshot-store
```

## Creating snapshots from event streams

This feature is provided by the [prooph/snapshotter](https://github.com/prooph/snapshotter) package.
Please refer to the docs of the package to learn more about it.

Also choose one of the `Prooph\*SnapshotStore` to take snapshots.
Inject the snapshot store into an aggregate repository and the repository will use the snapshot store to speed up
aggregate loading.

Our example application [proophessor-do](https://github.com/prooph/proophessor-do) contains a snapshotting tutorial.

## Using a different Serializer. 

By default prooph uses PHP's own serialise and unserialize methods. These may not suite your needs so as of v1.1 of the snapshot store you can use a custom serialiser. 

You can use the provided CallbackSerializer to do this.

```php
<?php

new PdoSnapshotStore(
   $connection,
   $config['snapshot_table_map'],
   $config['default_snapshot_table_name'],
   new CallbackSerializer('igbinary_serialize', 'igbinary_unserialize')
);
```

If you are using the interop factories all you have to do is create a Factory for `Prooph\SnapshotStore\Serializer` and add that as dependency;

```php
<?php

return [
	'dependencies' => [
		'factories' => [
		    \Prooph\SnapshotStore\Serializer::class => My\CallbackSerializerFactory::class,
		],
	],
];
``` 

*Note: All SnapshotStores ship with interop factories to ease set up.*

## Composite Snapshot Store

This component ships with a composite snapshot store, that aggregates multiple snapshot stores. When asked to save a
snapshot or removeAll, it will call the method in all aggregated snapshot stores. If you try to get a snapshot from the
composite, it will ask each snapshot store for the snapshot and returns the first snapshot found or null.

This is especially useful to combine a memcached snapshot store for high speed with a fallback like pdo or mongodb.

Example:

```php
<?php

$snapshotStore1 = new MemcachedSnapshotStore();
$snapshotStore2 = new MongoDbSnapshotStore();

$snapshotStore = new CompositeSnapshotStore($snapshotStore1, $snapshotStore2);
```

## Usage

Using [Prooph Event-Sourcing](https://github.com/prooph/event-sourcing/) you need to install [Prooph Snapshotter](https://github.com/prooph/snapshotter).
Check the documentation there on how to use it.

Using [Prooph Micro](https://github.com/prooph/micro/) the usage is a simple php function.
