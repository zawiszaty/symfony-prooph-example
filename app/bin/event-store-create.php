<?php

declare(strict_types=1);

require __DIR__.'./../vendor/autoload.php';

use App\Kernel;
use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;

$kernel = new Kernel('test', true);
$kernel->boot();
$container = $kernel->getContainer();
/** @var EventStore $eventStore */
$eventStore = $container->get('prooph_event_store.books_store');
$eventStore->create(new Stream(new StreamName('event_stream'), new \ArrayIterator([])));
