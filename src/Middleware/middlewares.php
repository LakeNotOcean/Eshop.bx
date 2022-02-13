<?php

use Up\Core\Middleware\MiddlewareManager;
use Up\Middleware\DebugMiddleware;
use Up\Middleware\Redirect404Middleware;
use Up\Middleware\URLAccessMiddleware\URLAccessMiddleware;

$middlewareManager = MiddlewareManager::getInstance();

$middlewares = [
	Redirect404Middleware::class,
	DebugMiddleware::class,
	URLAccessMiddleware::class
];

$middlewareManager->registerMiddlewares($middlewares);
