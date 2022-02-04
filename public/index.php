<?php

require '../Autoloader.php';


$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');

$migration= new \Up\Core\Migration\MigrationManager(\Up\Core\DataBase\DefaultDatabase::getInstance());
$migration->updateDatabase();

$dao = new \Up\Core\DAO\ItemDAOmysql(\Up\Core\DataBase\DefaultDatabase::getInstance());

var_dump($dao->getItems(0));

//var_dump($dao->getItemDetailById(2));
