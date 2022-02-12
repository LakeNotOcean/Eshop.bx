<?php

namespace Up\Core\Middleware;

abstract class AbstractMiddleware implements MiddlewareInterface
{
	protected $getResponse;

	public function __construct(callable $getResponse)
	{
		$this->getResponse = $getResponse;
	}
}