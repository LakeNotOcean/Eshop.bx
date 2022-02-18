<?php

namespace Up\Middleware;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;
use Up\Core\Router\Router;
use Up\Service\UserService\UserServiceInterface;


class RequestHandlerMiddleware extends AbstractMiddleware
{
	private $userService;

	/**
	 * @param \Up\Service\UserService\UserService $userService
	 */
	public function __construct(UserServiceInterface $userService)
	{
		$this->userService = $userService;
	}

	public function __invoke(Request $request, ...$params): Response
	{
		$request->setUser($this->userService->getUserInfo());
		$request->setRouteName(Router::getInstance()->getRouteName($request->getRequestUrl(), $request->getMethod()));
		return call_user_func($this->getResponse, $request, ...$params);
	}

}
