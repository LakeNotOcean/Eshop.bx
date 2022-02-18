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
		'make-order' => UserEnum::User,
	];
	private $userService;
	private $templateProcessor;

	/**
	 * @param \Up\Service\UserService\UserService $userService
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 */
	public function __construct(UserServiceInterface $userService, TemplateProcessorInterface $templateProcessor)
	{
		$this->userService = $userService;
		$this->templateProcessor = $templateProcessor;
	}

	/**
	 * @throws ResolveException
	 * @throws RoutingException
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
		$router = Router::getInstance();
		$urlName = $router->getRouteName($request->getRequestUrl());
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

	private function handleUrlWithNextParam(Request $request, array $params)
	{
		$router = Router::getInstance();
		$urlName = $router->getRouteName($request->getRequestUrl(), $request->getMethod());
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