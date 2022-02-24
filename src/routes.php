<?php

use Up\Controller\CartController;
use Up\Controller\CoreController;
use Up\Controller\ImageController;
use Up\Controller\ItemController;
use Up\Controller\OrderController;
use Up\Controller\ReviewController;
use Up\Controller\UserController;
use \Up\Controller\CategoryController;


$router = Up\Core\Router\Router::getInstance();

// Core
$router->get('/404', [CoreController::class, 'get404'], '404');

//User
$router->get('/', [ItemController::class, 'getItems'], 'home');
$router->get('/item/{positiveInt:id}', [ItemController::class, 'getItem'], 'item-detail');
$router->get('/item/{positiveInt:id}/reviews', [ItemController::class, 'moreReviews'], 'more-reviews');
$router->post('/reviewDelete/{positiveInt:id}', [ReviewController::class, 'deleteReview'], 'delete-review');
$router->post('/finishOrder', [OrderController::class, 'finishOrder'], 'finish-order');
$router->post('/addItemToCart', [CartController::class, 'addItemToCart'], 'add-item-to-cart');
$router->post('/deleteItemFromCart', [CartController::class, 'deleteItemFromCart'], 'delete-item-from-cart');
$router->get('/makeOrder', [OrderController::class, 'makeOrder'], 'make-order');

$router->get('/register', [UserController::class, 'registerUserPage'], 'register-user');
$router->get('/login', [UserController::class, 'loginUserPage'], 'login-user');
$router->post('/register', [UserController::class, 'registerUser'], 'register-user');
$router->post('/login', [UserController::class, 'loginUser'], 'login-user');
$router->get('/logout', [UserController::class, 'logout'], 'logout-user');
$router->get('/passwordChange', [UserController::class, 'changePasswordPage'], 'change-password');
$router->post('/passwordChange', [UserController::class, 'changePassword'], 'change-password');
$router->post('/addReview', [ReviewController::class, 'saveReview'], 'add-review');

$router->get('/profile', [UserController::class, 'getProfilePage'], 'user-profile');
$router->post('/updateUser', [UserController::class, 'updateUser'], 'update-user');
$router->get('/myPurchased', [ItemController::class, 'myPurchased'], 'my-purchased');

$router->get('/favorites', [ItemController::class, 'getFavoriteItems'], 'user-favorites');
$router->post('/addToFavorites', [ItemController::class, 'addToFavorites'], 'add-to-favorites');
$router->post('/removeFromFavorites', [ItemController::class, 'removeFromFavorites'], 'remove-from-favorites');
$router->get('/removeFromFavorites', [ItemController::class, 'removeFromFavorites'], 'remove-from-favorites');

//Admin
$router->get('/admin/addItem', [ItemController::class, 'addItem'], 'add-item');
$router->get('/admin/updateItem',[ItemController::class, 'updateItemPage'],'update-item');
$router->post('/admin/addItem', [ItemController::class, 'createNewItem'], 'add-item');
$router->get('/admin/chooseItemType', [CategoryController::class, 'chooseItemType'], 'choose-item-type');
$router->get('/admin/addItemType', [CategoryController::class, 'addItemType'], 'add-item-type');
$router->post('/admin/addItemType', [CategoryController::class, 'addItemTypeAndSaveToDB'], 'add-item-type');
$router->get('/admin/addCategory', [CategoryController::class, 'addCategory'], 'add-category');
$router->post('/admin/addCategory', [CategoryController::class, 'addCategoryAndSaveToDB'], 'add-category');
$router->get('/admin/addSpecification', [CategoryController::class, 'addSpecification'], 'add-specification');
$router->post('/admin/addSpecification', [CategoryController::class, 'addSpecificationAndSaveToDB'], 'add-specification');

$router->get('/admin/', [ItemController::class, 'getItems'], 'home-admin');
$router->get('/admin/deleteCategory', [CategoryController::class, 'deleteCategoryPage'], 'delete-category');
$router->post('/admin/deleteCategory', [CategoryController::class, 'deleteCategory'], 'delete-category');
$router->get('/admin/chooseCategory', [CategoryController::class, 'chooseCategoryToSpecDelete'], 'choose-category');
$router->get('/admin/deleteSpec/{positiveInt:id}',[CategoryController::class, 'deleteSpecPage'],'delete-specification');
$router->post('/admin/deleteSpecification', [CategoryController::class, 'deleteSpec'], 'delete-specification');
$router->get('/admin/editItem/{positiveInt:id}', [ItemController::class, 'addItem'], 'edit-item');
$router->post('/admin/deactivateItem/{positiveInt:id}', [ItemController::class, 'deactivateItem'], 'deactivate-item');
$router->post('/admin/activateItem/{positiveInt:id}', [ItemController::class, 'activateItem'], 'activate-item');
$router->get('/admin/acceptDeletionItem/{positiveInt:id}', [ItemController::class, 'acceptDeletion'], 'accept-deletion-item');
$router->post('/admin/deleteItem/{positiveInt:id}', [ItemController::class, 'realDeleteItem'], 'delete-item');
$router->post('/admin/fastUpdateItem', [ItemController::class, 'updateCommonInfo'], 'fast-item-update');
$router->post('/admin/deleteImage/{positiveInt:id}', [ImageController::class, 'deleteImageById'], 'delete-image');

$router->get('/admin/adminList',[UserController::class, 'adminListPage'],'admin-list');
$router->post('/admin/adminList',[UserController::class, 'removeAdmin'],'admin-list');

$router->get('/admin/editCategory', [CategoryController::class, 'editCategoriesPage'], 'edit-category');

$router->get('/admin/', [ItemController::class, 'getItems'], 'home-admin');
$router->get('/admin/getOrders', [OrderController::class, 'getOrders'], 'orders-admin');
$router->post('/admin/changeOrderStatus', [OrderController::class, 'changeOrderStatus'], 'order-change-status');
$router->post('/admin/deleteOrder', [OrderController::class, 'deleteOrder'], 'order-delete');

//API
$router->get('/api/v1/category/detail', [CategoryController::class, 'getCategoriesWithSpecsJSON'], 'category-detail');
$router->get('/api/v1/categories', [CategoryController::class, 'getCategoriesJSON'], 'cat');
$router->get('/api/v1/categoriesByType', [CategoryController::class, 'getCategoriesByItemTypeIdJSON'], 'categories-by-type');
$router->get('/api/v1/categoriesByItem/{positiveInt:id}', [CategoryController::class, 'getCategoriesByItemIdJSON'], 'categories-by-item');

$router->post('/test', [ReviewController::class, 'test'], 'test');
