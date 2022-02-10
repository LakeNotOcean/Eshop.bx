<?php

$router = Up\Core\Router\Router::getInstance();
$router->get('/', ['itemController', 'getItems'], 'home');
$router->get('/addItem', ['addItemController', 'addItem'], 'add-item');
$router->get('/item/{positiveInt:id}',['itemController', 'getItem'],'item-detail');
$router->post("/testPost", ['addItemController', 'test'], 'home');
$router->get('/categories', ['addItemController', 'getCategoriesJSON'], 'cat');
$router->get('/category/{positiveInt:id}', ['addItemController', 'getSpecsByCategoryIdJSON'], 'cat');
$router->get('/category/detail', ['addItemController', 'getCategoriesWithSpecsJSON'], 'category-detail');