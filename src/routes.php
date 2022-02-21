<?php

use Up\Controller\CoreController;
use Up\Controller\ImageController;
use Up\Controller\ItemController;
use Up\Controller\OrderController;
use Up\Controller\UserController;
use \Up\Controller\CategoryController;


$router = Up\Core\Router\Router::getInstance();

// Core
$router->get('/404', [CoreController::class, 'get404'], '404');

//User
$router->get('/', [ItemController::class, 'getItems'], 'home');
$router->get('/item/{positiveInt:id}', [ItemController::class, 'getItem'], 'item-detail');
$router->get('/makeOrder/{positiveInt:id}', [OrderController::class, 'makeOrder'], 'make-order');
$router->post('/finishOrder', [OrderController::class, 'finishOrder'], 'finish-order');
$router->get('/register', [UserController::class, 'registerUserPage'], 'register-user-page');
$router->get('/login', [UserController::class, 'loginUserPage'], 'login-user-page');
$router->post('/register', [UserController::class, 'registerUser'], 'register-user');
$router->post('/login', [UserController::class, 'loginUser'], 'login-user');
$router->get('/logout', [UserController::class, 'logout'], 'logout-user');

$router->get('/profile', [UserController::class, 'getProfilePage'], 'user-profile');
$router->post('/updateUser', [UserController::class, 'updateUser'], 'update-user');

//Admin
$router->get('/admin/addItem', [ItemController::class, 'addItem'], 'add-item');
$router->get('/admin/updateItem',[ItemController::class, 'updateItemPage'],'update-item-page');
$router->post('/admin/addItem', [ItemController::class, 'createNewItem'], 'add-item-db');
$router->get('/admin/chooseItemType', [CategoryController::class, 'chooseItemType'], 'choose-item-type');
$router->get('/admin/addItemType', [CategoryController::class, 'addItemType'], 'add-item-type');
$router->post('/admin/addItemType', [CategoryController::class, 'addItemTypeAndSaveToDB'], 'add-item-type-db');
$router->get('/admin/addCategory', [CategoryController::class, 'addCategory'], 'add-category');
$router->post('/admin/addCategory', [CategoryController::class, 'addCategoryAndSaveToDB'], 'add-category-db');
$router->get('/admin/addSpecification', [CategoryController::class, 'addSpecification'], 'add-specification');
$router->post('/admin/addSpecification', [CategoryController::class, 'addSpecificationAndSaveToDB'], 'add-specification-db');

$router->get('/admin/', [ItemController::class, 'getItems'], 'home-admin');
$router->get('/admin/deleteCategory', [CategoryController::class, 'deleteCategoryPage'], 'delete-category-page');
$router->post('/admin/deleteCategory', [CategoryController::class, 'deleteCategory'], 'delete-category-post');
$router->get('/admin/chooseCategory', [CategoryController::class, 'chooseCategoryToSpecDelete'], 'choose-category');
$router->get('/admin/deleteSpec/{positiveInt:id}',[CategoryController::class, 'deleteSpecPage'],'delete-specification-page');
$router->post('/admin/deleteSpecification', [CategoryController::class, 'deleteSpec'], 'delete-specification');
$router->get('/admin/editItem/{positiveInt:id}', [ItemController::class, 'addItem'], 'edit-item-page');
$router->post('/admin/deactivateItem/{positiveInt:id}', [ItemController::class, 'deactivateItem'], 'deactivate-item');
$router->post('/admin/fastUpdateItem', [ItemController::class, 'updateCommonInfo'], 'fast-item-update');
$router->post('/admin/deleteImage/{positiveInt:id}', [ImageController::class, 'deleteImageById'], 'delete-image');


$router->get('/admin/editCategory', [CategoryController::class, 'editCategoriesPage'], 'edit-category-page');

$router->get('/admin/', [ItemController::class, 'getItems'], 'home-admin');
$router->get('/admin/getOrders', [OrderController::class, 'getOrders'], 'orders-admin');
$router->post('/admin/changeOrderStatus', [OrderController::class, 'changeOrderStatus'], 'order-change-status');
$router->post('/admin/deleteOrder', [OrderController::class, 'deleteOrder'], 'order-delete');

//API
$router->get('/api/v1/category/detail', [CategoryController::class, 'getCategoriesWithSpecsJSON'], 'category-detail');
$router->get('/api/v1/categories', [CategoryController::class, 'getCategoriesJSON'], 'cat');
$router->get('/api/v1/categoriesByType', [CategoryController::class, 'getCategoriesByItemTypeIdJSON'], 'categories-by-type');
$router->get('/api/v1/categoriesByItem/{positiveInt:id}', [CategoryController::class, 'getCategoriesByItemIdJSON'], 'categories-by-item');
