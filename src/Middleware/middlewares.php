<?php


use Up\Core\Middleware\MiddlewareManager;
use Up\Middleware\CSRFMiddleware;
use Up\Middleware\DebugMiddleware;
use Up\Middleware\ErrorsForUserMiddleware;
use Up\Middleware\Redirect404Middleware;
use Up\Middleware\RequestHandlerMiddleware;
use Up\Middleware\URLAccessMiddleware;

$middlewareManager = MiddlewareManager::getInstance();

$middlewares = [
	Redirect404Middleware::class,
	DebugMiddleware::class,
	ErrorsForUserMiddleware::class,
	RequestHandlerMiddleware::class,
	URLAccessMiddleware::class,
	CSRFMiddleware::class
];

$middlewareManager->registerMiddlewares($middlewares);
