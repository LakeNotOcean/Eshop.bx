<?php

namespace Up\Core\Middleware;

use Up\Core\Message\Request;
use Up\Core\Message\Response;

interface MiddlewareInterface
{
	public function setResponseFunction(callable $getResponse);

	public function __invoke(Request $request, ...$params): Response;
}