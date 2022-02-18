<?php

namespace Up\Middleware;

use ReflectionException;
use Up\Controller\UserController;
use Up\Core\Enum\EnumException;
use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;
use Up\Core\Router\Error\ResolveException;
use Up\Core\Router\Error\RoutingException;
use Up\Core\Router\Router;
use Up\Core\TemplateProcessorInterface;
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
		'add-item' => UserEnum::Moderator,
		'update-item-page' => UserEnum::Moderator,
		'choose-item-type' => UserEnum::Moderator,
		'add-item-db' => UserEnum::Moderator,
		'add-item-type' => UserEnum::Moderator,
		'add-item-type-db' => UserEnum::Moderator,
		'add-category' => UserEnum::Moderator,
		'add-category-db' => UserEnum::Moderator,
		'add-specification' => UserEnum::Moderator,
		'add-specification-db' => UserEnum::Moderator,
		'home-admin' => UserEnum::Moderator,
		'delete-category-page' => UserEnum::Moderator,
		'delete-category-post' => UserEnum::Moderator,
		'choose-category' => UserEnum::Moderator,
		'delete-specification-page' => UserEnum::Moderator,
		'delete-specification' => UserEnum::Moderator,
		'edit-item-page' => UserEnum::Moderator,
		'deactivate-item' => UserEnum::Moderator,
		'fast-item-update' => UserEnum::Moderator,
		'delete-image' => UserEnum::Moderator,
		'edit-category-page' => UserEnum::Moderator,
		'orders-admin' => UserEnum::Moderator,
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
			return Redirect::createResponseByURLName('login-user-page', [
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
		$router = Router::getInstance();
		$urlName = $request->getRouteName();
		if ($urlName !== 'login-user-page' && $urlName !== 'login-user')
		{
			return Redirect::createResponseByURLName('home');
		}

		if ($urlName === 'login-user-page')
		{
			return call_user_func($this->getResponse, $request, ...$params);
		}

		$response = call_user_func($this->getResponse, $request, ...$params);
		if ($response->getStatusCode() !== 200)
		{
			return $response;
		}

		$nextUrl = $request->getQueriesByName(UserController::nextUrlQueryKeyword);
		if ($this->isValidUrl($nextUrl, $router))
		{
			return Redirect::createResponseByURL($nextUrl);
		}

		return Redirect::createResponseByURLName('home');
	}

	private function isValidUrl(string $url, Router $router): bool
	{
		try
		{
			$router->getRouteName($url);
			return true;
		}
		catch (RoutingException $exception)
		{
			return false;
		}
	}
}
