<?php

$router = Up\Core\Router\Router::getInstance();
$router->get('/', ['homeController', 'getItems'], 'home');
$router->get('/getItem',['homeController', 'getItem'],'home');
$router->get('/addItem', ['addItemController', 'addItem'], 'add-item');
