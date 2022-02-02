<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


require '../Autoloader.php';

$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');




$App = Up\Core\Application::run();
var_dump($App);