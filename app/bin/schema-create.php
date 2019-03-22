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
/** @var \Doctrine\DBAL\Connection $connection */
$connection = $container->get('doctrine')->getConnection();
$connection->beginTransaction();
$connection->query('DROP TABLE IF EXISTS `event_streams`;
CREATE TABLE `event_streams` (
  `no` bigint(20) NOT NULL AUTO_INCREMENT,
  `real_stream_name` varchar(150) COLLATE utf8_bin NOT NULL,
  `stream_name` char(41) COLLATE utf8_bin NOT NULL,
  `metadata` json DEFAULT NULL,
  `category` varchar(150) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`no`),
  UNIQUE KEY `ix_rsn` (`real_stream_name`),
  KEY `ix_cat` (`category`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
 ');
$connection->query('
DROP TABLE IF EXISTS `projections`;
CREATE TABLE `projections` (
  `no` bigint(20) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) COLLATE utf8_bin NOT NULL,
  `position` json DEFAULT NULL,
  `state` json DEFAULT NULL,
  `status` varchar(28) COLLATE utf8_bin NOT NULL,
  `locked_until` char(26) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`no`),
  UNIQUE KEY `ix_name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
 ');
$connection->commit();
$connection->close();
if (empty($sqls)) {
    echo "Nothing to Create \n";

    return 0;
}
echo "Schema Created \n";
