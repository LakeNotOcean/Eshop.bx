<?php

use Up\Controller\HomeController;

$router = Up\Core\Router\Router::getInstance();
$router->get('/', ['homeController', 'getItems'], 'home');
$router->get('/getItem',['homeController','getItem'],'getItem');
