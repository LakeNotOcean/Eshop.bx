<?php

require '../Autoloader.php';

$autoloader = Autoloader::getInstance();

$autoloader->addVendorNamespacePath('Up\\', __DIR__ . '/../src/');

$controller = new \Up\Controller\CatalogController();
echo $controller->getTemplate();
