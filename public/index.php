<?php

declare(strict_types=1);
const SOURCE_DIR = __DIR__ . '/../src/';

require '../Autoloader.php';


$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', SOURCE_DIR);

session_start();

Up\Core\Application::run();