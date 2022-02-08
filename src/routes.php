<?php

use Up\Controller\CatalogController;

$router = Up\Core\Router\Router::getInstance();
$router->get('/', ['catalogController', 'getItems'], 'home');
$router->get('/getItem',['catalogController','getItem'],'home');
