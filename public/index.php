<?php

require '../Autoloader.php';


$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');
$migration= new \Up\Core\Migration\MigrationManager(\Up\Core\DataBase\DefaultDatabase::getInstance());
$migration->updateDatabase();