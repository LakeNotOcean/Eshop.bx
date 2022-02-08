<?php

use Up\Controller\HomeController;

$router = Up\Core\Router\Router::getInstance();
$router->get('/', ['catalogController', 'get'], 'home');
