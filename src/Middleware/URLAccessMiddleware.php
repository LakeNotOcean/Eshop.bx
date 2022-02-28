<?php

namespace Up\Middleware;

use Up\Controller\UserController;
use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;
use Up\Core\Router\Error\ResolveException;
use Up\Core\Router\Error\RoutingException;
use Up\Entity\User\UserEnum;
use Up\Lib\Redirect;
use Up\Lib\URLHelper;
use Up\Service\UserService\UserServiceInterface;

class URLAccessMiddleware extends AbstractMiddleware
{
	/**
	 * @var array<string, string>
	 *     Сопоставляются названия путей и их права доступа. По умолчанию доступ - Guest
	 */
	private const accessConfig = [
		'admin:add-item' => UserEnum::Moderator,
		'admin:update-item' => UserEnum::Moderator,
		'admin:choose-item-type' => UserEnum::Moderator,
		'admin:add-item-type' => UserEnum::Moderator,
		'admin:add-category' => UserEnum::Moderator,
		'admin:add-specification' => UserEnum::Moderator,
		'admin:home' => UserEnum::Moderator,
		'admin:delete-category' => UserEnum::Moderator,
		'admin:choose-category' => UserEnum::Moderator,
		'admin:delete-specification' => UserEnum::Moderator,
		'admin:edit-item' => UserEnum::Moderator,
		'admin:deactivate-item' => UserEnum::Moderator,
		'admin:fast-item-update' => UserEnum::Moderator,
		'admin:delete-image' => UserEnum::Moderator,
		'admin:edit-category' => UserEnum::Moderator,
		'admin:orders' => UserEnum::Moderator,
	];
	private $userService;

	/**
	 * @param \Up\Service\UserService\UserService $userService
	 */
	public function __construct(UserServiceInterface $userService)
	{
		$this->userService = $userService;
	}

	/**
	 * @throws ResolveException
	 * @throws RoutingException
	 * @throws NoSuchQueryParameterException
	 */
	public function __invoke(Request $request, ...$params): Response
	{
		if (URLHelper::urlContainsParam($request->getRequestUrl(), UserController::nextUrlQueryKeyword))
		{
			return $this->handleUrlWithNextParam($request, $params);
		}
		return $this->handleUrlWithoutNextParam($request, $params);
	}

	/**
	 * @throws RoutingException|ResolveException
	 */
	private function handleUrlWithoutNextParam(Request $request, array $params)
	{
		$urlName = $request->getRouteName();
		if (!array_key_exists($urlName, static::accessConfig))
		{
			return call_user_func($this->getResponse, $request, ...$params);
		}
		$user = $this->userService->getUserInfo();
		$userRoleName = $user->getRole()->getName()->getValue();
		if ($userRoleName === UserEnum::Guest)
		{
			return Redirect::createResponseByURLName('login-user', [
				UserController::nextUrlQueryKeyword => URLHelper::removeIfExistGetParametersFromPath($request->getRequestUrl())
			]);
		}

		$availableRole = new UserEnum(static::accessConfig[$urlName]);
		if ($this->userService->hasPermission($availableRole))
		{
			return call_user_func($this->getResponse, $request, ...$params);
		}

		return Redirect::createResponseByURLName('404');
	}

	/**
	 * @throws NoSuchQueryParameterException
	 * @throws ResolveException
	 * @throws RoutingException
	 */
	private function handleUrlWithNextParam(Request $request, array $params)
	{
		$urlName = $request->getRouteName();
		if ($urlName !== 'login-user')
		{
			return Redirect::createResponseByURLName('home');
		}

		return call_user_func($this->getResponse, $request, ...$params);
	}
}
