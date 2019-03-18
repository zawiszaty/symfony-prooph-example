<?php

require __DIR__ . './../vendor/autoload.php';

use App\Kernel;
use Doctrine\DBAL\DriverManager;

$kernel = new Kernel('test', true);
$kernel->boot();
$container = $kernel->getContainer();
$connectionName = $container->get('doctrine')->getDefaultConnectionName();
/** @var \Doctrine\DBAL\Connection $connection */
$connection = $container->get('doctrine')->getConnection($connectionName);
$params = $connection->getParams();
// Cannot inject `shard` option in parent::getDoctrineConnection
// cause it will try to connect to a non-existing database
if (isset($params['shards'])) {
    $shards = $params['shards'];
    // Default select global
    $params = array_merge($params, $params['global']);
    unset($params['global']['dbname']);
    if ($input->getOption('shard')) {
        foreach ($shards as $i => $shard) {
            if ($shard['id'] === (int)$input->getOption('shard')) {
                // Select sharded database
                $params = array_merge($params, $shard);
                unset($params['shards'][$i]['dbname'], $params['id']);
                break;
            }
        }
    }
}

$hasPath = isset($params['path']);
$name = $hasPath ? $params['path'] : (isset($params['dbname']) ? $params['dbname'] : false);
if (!$name) {
    throw new \InvalidArgumentException("Connection does not contain a 'path' or 'dbname' parameter and cannot be dropped.");
}
// Need to get rid of _every_ occurrence of dbname from connection configuration and we have already extracted all relevant info from url
unset($params['dbname'], $params['path'], $params['url']);

$tmpConnection = DriverManager::getConnection($params);
$tmpConnection->connect($params['shards']);
$shouldNotCreateDatabase = $ifNotExists && in_array($name, $tmpConnection->getSchemaManager()->listDatabases());

// Only quote if we don't have a path
if (!$hasPath) {
    $name = $tmpConnection->getDatabasePlatform()->quoteSingleIdentifier($name);
}

$error = false;
try {
    if ($shouldNotCreateDatabase) {
        echo 'error';
//        $output->writeln(sprintf('<info>Database <comment>%s</comment> for connection named <comment>%s</comment> already exists. Skipped.</info>', $name, $connectionName));
    } else {
        $tmpConnection->getSchemaManager()->dropDatabase($name);
        echo "Database $name dropped \n";
//        $output->writeln(sprintf('<info>Created database <comment>%s</comment> for connection named <comment>%s</comment></info>', $name, $connectionName));
    }
} catch (\Exception $e) {
//    $output->writeln(sprintf('<error>Could not create database <comment>%s</comment> for connection named <comment>%s</comment></error>', $name, $connectionName));
//    $output->writeln(sprintf('<error>%s</error>', $e->getMessage()));
    echo $e->getMessage();
    $error = true;
}

$tmpConnection->close();