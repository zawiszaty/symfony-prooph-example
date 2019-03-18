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
echo "Schema creating.... \n";
$schemaTool->createSchema($metadatas);
if (empty($sqls)) {
    echo "Nothing to Create \n";

    return 0;
}
echo "Schema Created \n";
