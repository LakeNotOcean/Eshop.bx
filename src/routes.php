<?php

use Up\Controller\CartController;
use Up\Controller\CoreController;
use Up\Controller\ImageController;
use Up\Controller\ItemController;
use Up\Controller\OrderController;
use Up\Controller\ReviewController;
use Up\Controller\UserController;
use Up\Controller\CategoryController;

$router = Up\Core\Router\Router::getInstance();

// Core
$router->get('/404', [CoreController::class, 'get404'], '404');

//User
$router->get('/', [ItemController::class, 'setTypeItem'], 'set-type-item');
$router->get('/catalog', [ItemController::class, 'getItems'], 'home');
$router->get('/item/{positiveInt:id}', [ItemController::class, 'getItem'], 'item-detail');
$router->get('/item/{positiveInt:id}/reviews', [ItemController::class, 'moreReviews'], 'more-reviews');
$router->post('/reviewDelete/{positiveInt:id}', [ReviewController::class, 'deleteReview'], 'delete-review');
$router->post('/finishOrder', [OrderController::class, 'finishOrder'], 'finish-order');
$router->post('/addItemToCart', [CartController::class, 'addItemToCart'], 'add-item-to-cart');
$router->post('/deleteItemFromCart', [CartController::class, 'deleteItemFromCart'], 'admin:delete-item-from-cart');
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
$router->get('/myOrders', [OrderController::class, 'getMyOrders'], 'my-orders');

$router->get('/favorites', [ItemController::class, 'getFavoriteItems'], 'user-favorites');
$router->post('/addToFavorites', [ItemController::class, 'addToFavorites'], 'add-to-favorites');
$router->post('/removeFromFavorites', [ItemController::class, 'removeFromFavorites'], 'remove-from-favorites');
$router->get('/removeFromFavorites', [ItemController::class, 'removeFromFavorites'], 'remove-from-favorites');

//Admin
$router->get('/admin/addItem', [ItemController::class, 'addItem'], 'admin:add-item');
$router->get('/admin/updateItem',[ItemController::class, 'updateItemPage'],'admin:update-item');
$router->post('/admin/addItem', [ItemController::class, 'createNewItem'], 'admin:add-item');
$router->get('/admin/chooseItemType', [CategoryController::class, 'chooseItemType'], 'admin:choose-item-type');
$router->get('/admin/addItemType', [CategoryController::class, 'addItemType'], 'admin:add-item-type');
$router->post('/admin/addItemType', [CategoryController::class, 'addItemTypeAndSaveToDB'], 'admin:add-item-type');
$router->get('/admin/addCategory', [CategoryController::class, 'addCategory'], 'admin:add-category');
$router->post('/admin/addCategory', [CategoryController::class, 'addCategoryAndSaveToDB'], 'admin:add-category');
$router->get('/admin/addSpecification', [CategoryController::class, 'addSpecification'], 'admin:add-specification');
$router->post('/admin/addSpecification', [CategoryController::class, 'addSpecificationAndSaveToDB'], 'admin:add-specification');

$router->get('/admin/', [ItemController::class, 'getItems'], 'admin:home');
$router->get('/admin/deleteCategory', [CategoryController::class, 'deleteCategoryPage'], 'admin:delete-category');
$router->post('/admin/deleteCategory', [CategoryController::class, 'deleteCategory'], 'admin:delete-category');
$router->get('/admin/chooseCategory', [CategoryController::class, 'chooseCategoryToSpecDelete'], 'admin:choose-category');
$router->get('/admin/deleteSpec/{positiveInt:id}',[CategoryController::class, 'deleteSpecPage'],'admin:delete-specification-page');
$router->post('/admin/deleteSpec', [CategoryController::class, 'deleteSpec'], 'admin:delete-specification');
$router->get('/admin/editItem/{positiveInt:id}', [ItemController::class, 'addItem'], 'admin:edit-item');
$router->post('/admin/deactivateItem/{positiveInt:id}', [ItemController::class, 'deactivateItem'], 'admin:deactivate-item');
$router->post('/admin/activateItem/{positiveInt:id}', [ItemController::class, 'activateItem'], 'admin:activate-item');
$router->post('/admin/deleteItem/{positiveInt:id}', [ItemController::class, 'realDeleteItem'], 'admin:delete-item');
$router->post('/admin/fastUpdateItem', [ItemController::class, 'updateCommonInfo'], 'admin:fast-item-update');
$router->post('/admin/deleteImage/{positiveInt:id}', [ImageController::class, 'deleteImageById'], 'admin:delete-image');

$router->get('/admin/adminList',[UserController::class, 'adminListPage'],'admin:admin-list');
$router->post('/admin/adminList',[UserController::class, 'removeAdmin'],'admin:admin-list');
$router->get('/admin/userList',[UserController::class, 'userListPage'],'admin:user-list');
$router->post('/admin/adminUpdateUser/{positiveInt:id}',[UserController::class, 'adminUpdateUser'],'admin:update-user');
$router->get('/admin/userList/{positiveInt:id}',[UserController::class, 'userInfoPage'],'admin:user-info');

$router->get('/admin/editCategory', [CategoryController::class, 'editCategoriesPage'], 'admin:edit-category');

$router->get('/admin/getOrders', [OrderController::class, 'getOrders'], 'admin:orders');
$router->post('/admin/changeOrderStatus', [OrderController::class, 'changeOrderStatus'], 'admin:order-change-status');
$router->post('/admin/deleteOrder', [OrderController::class, 'deleteOrder'], 'admin:order-delete');

//API
$router->get('/api/v1/category/detail', [CategoryController::class, 'getCategoriesWithSpecsJSON'], 'apiV1:category-detail');
$router->get('/api/v1/categories', [CategoryController::class, 'getCategoriesJSON'], 'apiV1:categories');
$router->get('/api/v1/categoriesByType', [CategoryController::class, 'getCategoriesByItemTypeIdJSON'], 'apiV1:categories-by-type');
$router->get('/api/v1/categoriesByItem/{positiveInt:id}', [CategoryController::class, 'getCategoriesByItemIdJSON'], 'apiV1:categories-by-item');
