<?php

namespace Up\Core\Middleware;

abstract class AbstractMiddleware implements MiddlewareInterface
{
	protected $getResponse;

	public function setResponseFunction(callable $getResponse)
	{
		$this->getResponse = $getResponse;
	}
}