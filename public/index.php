<?php

require '../Autoloader.php';

$autoloader = Autoloader::getInstance();
$autoloader->addVendorNamespacePath('Up\\',$_SERVER['DOCUMENT_ROOT'] . '/..');


var_dump(\Up\Core\Test::test());
/*$App = Up\Core\Application::run();
var_dump($App);*/