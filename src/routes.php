<?php

$router = Up\Core\Router\Router::getInstance();
$router->get('/', ['catalogController', 'getItems'], 'home');
$router->get('/getItem',['catalogController', 'getItem'],'home');
$router->get('/addItem', ['addItemController', 'addItem'], 'add-item');
$router->get('/item/{positiveInt:id}',['catalogController', 'getItem'],'item-detail');