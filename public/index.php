<?php

require '../Autoloader.php';

$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');

Up\Core\Application::run();