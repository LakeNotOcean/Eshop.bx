<?php

require '../Autoloader.php';

$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');

var_dump(\Up\Core\Message\Request::createFromGlobals());