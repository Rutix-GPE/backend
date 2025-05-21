<?php

use Symfony\Component\Dotenv\Dotenv;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;


require dirname(__DIR__).'/vendor/autoload.php';

if (method_exists(Dotenv::class, 'bootEnv')) {
    (new Dotenv())->bootEnv(dirname(__DIR__).'/.env');
}

if ($_SERVER['APP_DEBUG']) {
    umask(0000);
}


$kernel = new \App\Kernel('test', true);
$kernel->boot();

$container = $kernel->getContainer();
$em = $container->get('doctrine')->getManager();
$connection = $em->getConnection();

$connection->executeStatement('SET FOREIGN_KEY_CHECKS=0');
foreach ($em->getMetadataFactory()->getAllMetadata() as $meta) {
    $table = $meta->getTableName();
    $connection->executeStatement("TRUNCATE TABLE `$table`");
}
$connection->executeStatement('SET FOREIGN_KEY_CHECKS=1');
