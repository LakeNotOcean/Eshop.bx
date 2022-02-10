<?php

$router = Up\Core\Router\Router::getInstance();

$router->post("/testPost", ['addItemController', 'test'], 'home');

//User
$router->get('/', ['itemController', 'getItems'], 'home');
$router->get('/getItem',['itemController', 'getItem'],'home');
$router->get('/item/{positiveInt:id}',['itemController', 'getItem'],'item-detail');
$router->get('/makeOrder/{positiveInt:id}',['orderController', 'makeOrder'],'make-order');
$router->post('/finishOrder',['orderController', 'finishOrder'],'finish-order');

//Admin
$router->get('/addItem', ['addItemController', 'addItem'], 'add-item');
$router->get('/category/detail', ['addItemController', 'getCategoriesWithSpecsJSON'], 'category-detail');
$router->get('/categories', ['addItemController', 'getCategoriesJSON'], 'cat');
$router->get('/category/{positiveInt:id}', ['addItemController', 'getSpecsByCategoryIdJSON'], 'cat');