<?php


require '../Autoloader.php';

$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');






$App = Up\Core\Application::run();
var_dump($App);