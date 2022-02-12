<?php

$middlewareManager = \Up\Core\Middleware\MiddlewareManager::getInstance();

$middlewares = [
	\Up\Middleware\Redirect404Middleware::class,
	\Up\Middleware\DebugMiddleware::class,
];

$middlewareManager->registerMiddlewares($middlewares);
