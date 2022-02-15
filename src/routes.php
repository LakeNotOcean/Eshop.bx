<?php

use Up\Controller\ItemController;
use Up\Controller\OrderController;
use Up\Controller\UserController;
use \Up\Controller\CategoryController;


$router = Up\Core\Router\Router::getInstance();



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
$router->get('/admin/addItem', [ItemController::class, 'addItem'], 'add-item');
$router->post('/admin/addItem', [ItemController::class, 'createNewItem'], 'home');
$router->get('/admin/chooseItemType', [CategoryController::class, 'chooseItemType'], 'choose-item-type');
$router->get('/admin/addItemType', [CategoryController::class, 'addItemType'], 'add-item-type');
$router->post('/admin/addItemType', [CategoryController::class, 'addItemTypeAndSaveToDB'], 'add-item-type-db');
$router->get('/admin/addCategory', [CategoryController::class, 'addCategory'], 'add-category');
$router->post('/admin/addCategory', [CategoryController::class, 'addCategoryAndSaveToDB'], 'add-category-db');
$router->get('/admin/addSpecification', [CategoryController::class, 'addSpecification'], 'add-specification');
$router->post('/admin/addSpecification', [CategoryController::class, 'addSpecificationAndSaveToDB'], 'add-specification-db');
$router->get('/admin/', [ItemController::class, 'getItems'], 'home-admin');
$router->get('/admin/editCategory', [CategoryController::class, 'editCategoriesPage'], 'edit-category-page');

//API
$router->get('/api/v1/category/detail', [CategoryController::class, 'getCategoriesWithSpecsJSON'], 'category-detail');
$router->get('/api/v1/categories', [CategoryController::class, 'getCategoriesJSON'], 'cat');
$router->get('/api/v1/categoriesByType', [CategoryController::class, 'getCategoriesByItemTypeIdJSON'], 'categories-by-type');
