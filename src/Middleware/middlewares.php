<?php

use Up\Core\Middleware\MiddlewareManager;
use Up\Middleware\CSRFMiddleware;
use Up\Middleware\DebugMiddleware;
use Up\Middleware\Redirect404Middleware;
use Up\Middleware\RequestHandlerMiddleware;
use Up\Middleware\URLAccessMiddleware;

$middlewareManager = MiddlewareManager::getInstance();

$middlewares = [
	Redirect404Middleware::class,
	DebugMiddleware::class,
	RequestHandlerMiddleware::class,
	URLAccessMiddleware::class,
	CSRFMiddleware::class
];

$middlewareManager->registerMiddlewares($middlewares);
