<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


require '../Autoloader.php';

$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');

$migration= new \Up\Core\Migration\MigrationManager(\Up\Core\DataBase\DefaultDatabase::getInstance());
$migration->updateDatabase();



$App = Up\Core\Application::run();
var_dump($App);