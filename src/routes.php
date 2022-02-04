<?php

use Up\Controller\CatalogController;

$router = Up\Core\Router\Router::getInstance();
$router->get('/home', [CatalogController::class, 'getItems'], '/home');
$router->get('/', [CatalogController::class, 'getItems'], '/home');
