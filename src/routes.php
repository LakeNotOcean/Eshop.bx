<?php

$router = Up\Core\Router\Router::getInstance();
$router->get('/', ['catalogController', 'getItems'], 'home');
$router->get('/getItem',['catalogController', 'getItem'],'home');
$router->get('/addItem', ['addItemController', 'addItem'], 'add-item');
$router->get('/item/{positiveInt:id}',['catalogController', 'getItem'],'item-detail');
$router->post("/testPost", ['addItemController', 'test'], 'home');
$router->get('/categories', ['addItemController', 'getCategoriesJSON'], 'cat');
$router->get('/category/{positiveInt:id}', ['addItemController', 'getSpecsByCategoryIdJSON'], 'cat');
$router->get('/category/detail', ['addItemController', 'getCategoriesWithSpecsJSON'], 'category-detail');