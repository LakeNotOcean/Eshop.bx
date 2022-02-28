<?php

namespace Up\Middleware;

use Up\Core\Error\ExceptionWithMessagesForUser;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;

class ErrorsForUserMiddleware extends AbstractMiddleware
{
	public function __invoke(Request $request, ...$params): Response
	{
		try
		{
			return call_user_func($this->getResponse, $request, ...$params);
		}
		catch (ExceptionWithMessagesForUser $exception)
		{
			return (new Response())->withBodyJSON($exception->getMessages())->withStatus($exception->getStatus());
		}
	}
}