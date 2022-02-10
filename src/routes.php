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
$router->get('/chooseItemType', ['addItemController', 'chooseItemType'], 'choose-item-type');
$router->get('/addItemType', ['addItemController', 'addItemType'], 'add-item-type');
$router->get('/addCategory', ['addItemController', 'addCategory'], 'add-category');
$router->post('/addCategory', ['addItemController', 'addCategoryAndSaveToDB'], 'add-category-db');
$router->get('/addSpecification', ['addItemController', 'addSpecification'], 'add-specification');
$router->post('/addSpecification', ['addItemController', 'addSpecificationAndSaveToDB'], 'add-specification-db');

$router->get('/category/detail', ['addItemController', 'getCategoriesWithSpecsJSON'], 'category-detail');
$router->get('/categories', ['addItemController', 'getCategoriesJSON'], 'cat');
$router->get('/category/{positiveInt:id}', ['addItemController', 'getSpecsByCategoryIdJSON'], 'cat');
