<?php

declare(strict_types=1);

require __DIR__.'./../vendor/autoload.php';

use App\Kernel;
use Doctrine\ORM\Tools\SchemaTool;

$kernel = new Kernel('test', true);
$kernel->boot();
$container = $kernel->getContainer();

$manager = $container->get('doctrine')->getManager();
$metadatas = $manager->getMetadataFactory()->getAllMetadata();
$schemaTool = new SchemaTool($manager);
$sqls = $schemaTool->getCreateSchemaSql($metadatas);
echo "Schema droping.... \n";
$schemaTool->dropSchema($metadatas);
/** @var \Doctrine\DBAL\Connection $connection */
$connection = $container->get('doctrine')->getConnection();
$connection->beginTransaction();
$connection->query('DROP TABLE `event_streams`;');
$connection->query('DROP TABLE `projections`;');
$connection->commit();
$connection->close();
if (empty($sqls)) {
    echo "Nothing to Drop \n";

    return 0;
}
echo "Schema Drop \n";
