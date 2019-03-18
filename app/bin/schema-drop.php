<?php

require __DIR__ . './../vendor/autoload.php';

use App\Kernel;
use Doctrine\DBAL\DriverManager;
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
if (empty($sqls)) {
    echo "Nothing to Drop \n";

    return 0;
}
echo "Schema Drop \n";