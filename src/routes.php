<?php

use Up\Controller\AddItemController;
use Up\Controller\ItemController;
use Up\Controller\OrderController;
use Up\Controller\UserController;


$router = Up\Core\Router\Router::getInstance();

$router->post("/testPost", [AddItemController::class, 'test'], 'home');

//User
$router->get('/', [ItemController::class, 'getItems'], 'home');
$router->get('/item/{positiveInt:id}', [ItemController::class, 'getItem'], 'item-detail');
$router->get('/makeOrder/{positiveInt:id}', [OrderController::class, 'makeOrder'], 'make-order');
$router->post('/finishOrder', [OrderController::class, 'finishOrder'], 'finish-order');
$router->get('/register', [UserController::class, 'registerUserPage'], 'register-user-page');
$router->get('/login', [UserController::class, 'loginUserPage'], 'login-user-page');
$router->post('/register', [UserController::class, 'registerUser'], 'register-user');
$router->post('/login', [UserController::class, 'loginUser'], 'login-user');

//Admin
$router->get('/addItem', [AddItemController::class, 'addItem'], 'add-item');
$router->get('/chooseItemType', [AddItemController::class, 'chooseItemType'], 'choose-item-type');
$router->get('/addItemType', [AddItemController::class, 'addItemType'], 'add-item-type');
$router->post('/addItemType', [AddItemController::class, 'addItemTypeAndSaveToDB'], 'add-item-type-db');
$router->get('/addCategory', [AddItemController::class, 'addCategory'], 'add-category');
$router->post('/addCategory', [AddItemController::class, 'addCategoryAndSaveToDB'], 'add-category-db');
$router->get('/addSpecification', [AddItemController::class, 'addSpecification'], 'add-specification');
$router->post('/addSpecification', [AddItemController::class, 'addSpecificationAndSaveToDB'], 'add-specification-db');

$router->get('/category/detail', [AddItemController::class, 'getCategoriesWithSpecsJSON'], 'category-detail');
$router->get('/categories', [AddItemController::class, 'getCategoriesJSON'], 'cat');
$router->get('/category/{positiveInt:id}', [AddItemController::class, 'getSpecsByCategoryIdJSON'], 'cat');

$router->get('/categoriesByType', [AddItemController::class, 'getCategoriesByItemTypeIdJSON'], 'categories-by-type');
