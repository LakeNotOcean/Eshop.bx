<?php

$router = Up\Core\Router\Router::getInstance();

//User
$router->get('/', ['catalogController', 'getItems'], 'home');
$router->get('/getItem',['catalogController', 'getItem'],'home');
$router->get('/item/{positiveInt:id}',['catalogController', 'getItem'],'item-detail');
$router->get('/makeOrder/{positiveInt:id}',['orderController', 'makeOrder'],'make-order');

//Admin
$router->get('/addItem', ['addItemController', 'addItem'], 'add-item');
