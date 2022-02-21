<?php

namespace Up\Middleware;

use Throwable;
use Up\Core\Logger\LoggerInterface;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;
use Up\Lib\Redirect;

class Redirect404Middleware extends AbstractMiddleware
{
	private $logger;

	/**
	 * @param \Up\Core\Logger\Application\ApplicationLogger $logger
	 */
	public function __construct(LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	public function __invoke(Request $request, ...$params): Response
	{
		try
		{
			return call_user_func($this->getResponse, $request, ...$params);
		}
		catch (Throwable $throwable)
		{
			$this->logger->error($throwable, [
				'method' => $request->getMethod(),
				'url' => $request->getRequestUrl(),
				'cookie' => $request->getCookies(),
				'session' => $request->getSession(),
			]);

			return Redirect::createResponseByURL('404');
		}
	}
}
