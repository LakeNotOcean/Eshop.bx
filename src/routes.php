<?php

$router = Up\Core\Router\Router::getInstance();
$router->get('/home',[\Up\Controller\CatalogController::class, 'getItems'],'/home');
