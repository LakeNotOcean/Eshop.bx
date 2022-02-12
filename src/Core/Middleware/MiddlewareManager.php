<?php

namespace Up\Core\Middleware;

use Up\Core\Message\Request;
use Up\Core\Message\Response;

class MiddlewareManager
{
	protected $middlewareClassNames = [];
	private static $instance;
	private $middlewaresLoadingFilePaths = [
		SOURCE_DIR . 'Middleware/middlewares.php',
	];

	public function registerMiddleware(MiddlewareInterface $middleware)
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
		if (empty($this->middlewareClassNames))
		{
			return $processedFunc($request, ...$params);
		}

		$reversedMiddlewareOrder = array_reverse($this->middlewareClassNames);
		$registeredMiddlewares = [
			new $reversedMiddlewareOrder[0]($processedFunc),
		];

		for ($index = 1, $indexMax = count($reversedMiddlewareOrder); $index < $indexMax; $index++)
		{
			$registeredMiddlewares[] = new $reversedMiddlewareOrder[$index]($registeredMiddlewares[$index - 1]);
		}

		return end($registeredMiddlewares)($request, ...$params);
	}

	public function loadMiddlewares()
	{
		foreach ($this->middlewaresLoadingFilePaths as $middlewaresLoadingFilePath)
		{
			require_once $middlewaresLoadingFilePath;
		}
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