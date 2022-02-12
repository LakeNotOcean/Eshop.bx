<?php

declare(strict_types=1);

require '../Autoloader.php';


$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');

session_start();

Up\Core\Application::run();
