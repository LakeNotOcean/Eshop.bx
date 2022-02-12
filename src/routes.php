<?php

// $router = Up\Core\Router\Router::getInstance();
//
// $router->post("/testPost", ['addItemController', 'test'], 'home');
//
// //User
// $router->get('/', ['itemController', 'getItems'], 'home');
// $router->get('/item/{positiveInt:id}',['itemController', 'getItem'],'item-detail');
// $router->get('/makeOrder/{positiveInt:id}',['orderController', 'makeOrder'],'make-order');
// $router->get('/register',['userController','registerUserPage'],'register-user-page');
// $router->get('/login',['userController','loginUserPage'],'login-user-page');
// $router->post('/finishOrder',['orderController', 'finishOrder'],'finish-order');
// $router->post('/register',['userController','registerUser'],'register-user');
// $router->post('/login',['userController','loginUser'],'login-user');
// //Admin
// $router->get('/addItem', ['addItemController', 'addItem'], 'add-item');
// $router->get('/chooseItemType', ['addItemController', 'chooseItemType'], 'choose-item-type');
// $router->get('/addItemType', ['addItemController', 'addItemType'], 'add-item-type');
// $router->post('/addItemType', ['addItemController', 'addItemTypeAndSaveToDB'], 'add-item-type-db');
// $router->get('/addCategory', ['addItemController', 'addCategory'], 'add-category');
// $router->post('/addCategory', ['addItemController', 'addCategoryAndSaveToDB'], 'add-category-db');
// $router->get('/addSpecification', ['addItemController', 'addSpecification'], 'add-specification');
// $router->post('/addSpecification', ['addItemController', 'addSpecificationAndSaveToDB'], 'add-specification-db');
//
// $router->get('/category/detail', ['addItemController', 'getCategoriesWithSpecsJSON'], 'category-detail');
// $router->get('/categories', ['addItemController', 'getCategoriesJSON'], 'cat');
// $router->get('/category/{positiveInt:id}', ['addItemController', 'getSpecsByCategoryIdJSON'], 'cat');
//
// $router->get('/categoriesByType', ['addItemController', 'getCategoriesByItemTypeIdJSON'], 'categories-by-type');





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
