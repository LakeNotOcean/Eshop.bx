<?php

namespace Up\Core\Middleware;

use Up\Core\DI\DIContainer;
use Up\Core\Message\Request;
use Up\Core\Message\Response;


class MiddlewareManager
{
	protected $middlewareClassNames = [];
	private static $instance;
	private $middlewaresLoadingFilePath = SOURCE_DIR . 'Middleware/middlewares.php';

	public function registerMiddleware(string $middleware)
	{
		$this->middlewareClassNames[] = $middleware;
	}

	/**
	 * @param array<MiddlewareInterface> $middlewaresClassName
	 *
	 * @return void
	 */
	public function registerMiddlewares(array $middlewaresClassName)
	{
		$this->middlewareClassNames = array_merge($this->middlewareClassNames, $middlewaresClassName);
	}

	public function invokeWithMiddleware(callable $processedFunc, Request $request, ...$params): Response
	{
		$diContainer = DIContainer::getInstance();
		if (empty($this->middlewareClassNames))
		{
			return $processedFunc($request, ...$params);
		}

		$reversedMiddlewareOrder = array_reverse($this->middlewareClassNames);

		$lastMiddleware = $diContainer->get($reversedMiddlewareOrder[0]);
		$lastMiddleware->setResponseFunction($processedFunc);

		$registeredMiddlewares = [$lastMiddleware];

		for ($index = 1, $indexMax = count($reversedMiddlewareOrder); $index < $indexMax; $index++)
		{
			$nextMiddleware = $diContainer->get($reversedMiddlewareOrder[$index]);
			$nextMiddleware->setResponseFunction($registeredMiddlewares[$index - 1]);
			$registeredMiddlewares[] = $nextMiddleware;
		}

		return end($registeredMiddlewares)($request, ...$params);
	}

	public function loadMiddlewares()
	{
		require_once $this->middlewaresLoadingFilePath;
	}

	public static function getInstance(): self
	{
		if (!isset(static::$instance))
		{
			static::$instance = new static();
		}

		return static::$instance;
	}
}
