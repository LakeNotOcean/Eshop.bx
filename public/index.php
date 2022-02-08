<?php

declare(strict_types=1);

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


require '../Autoloader.php';

$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');


$dao = new \Up\DAO\ItemDAOmysql(\Up\Core\Database\DefaultDatabase::getInstance());
var_dump($dao->getItemDetailById(2));


$App = Up\Core\Application::run();
var_dump($App);