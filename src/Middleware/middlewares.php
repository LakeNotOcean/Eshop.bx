<?php

use Up\Core\Middleware\MiddlewareManager;
use Up\Middleware\DebugMiddleware;
use Up\Middleware\Redirect404Middleware;

$middlewareManager = MiddlewareManager::getInstance();

$middlewares = [
	Redirect404Middleware::class,
	DebugMiddleware::class,
];

$middlewareManager->registerMiddlewares($middlewares);
