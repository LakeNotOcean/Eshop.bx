<?php

namespace Up\Middleware;

use Throwable;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;
use Up\Core\Settings\Settings;
use Up\Core\TemplateProcessorImpl;

class DebugMiddleware extends AbstractMiddleware
{
	public function __invoke(Request $request, ...$params): Response
	{
		if (Settings::getInstance()->getSettings('isDev'))
		{
			try
			{
				return call_user_func($this->getResponse, $request, ...$params);
			}
			catch (Throwable $throwable)
			{
				return (new Response())->withBodyHTML(
					(new TemplateProcessorImpl())->render('debug.php', [
						'request' => $request,
						'exception' => $throwable,
					],                                    'main.php', [])
				);
			}
		}

		return call_user_func($this->getResponse, $request, ...$params);
	}
}