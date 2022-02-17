<?php

namespace Up\Middleware;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;
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
		return call_user_func($this->getResponse, $request, ...$params);
	}

}
