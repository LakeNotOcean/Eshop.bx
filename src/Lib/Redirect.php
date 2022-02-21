<?php

namespace Up\Lib;

use Up\Core\Message\Response;
use Up\Core\Router\Error\ResolveException;
use Up\Core\Router\URLResolver;

class Redirect
{
	/**
	 * @param string $urlName
	 * @param array $queryStringParam Тут передаются get параметры
	 * @param array $urlParam Тут передаются параметры для шаблона пути
	 *
	 * @return Response
	 * @throws ResolveException
	 */
	public static function createResponseByURLName(string $urlName, array $queryStringParam = [], array $urlParam = []): Response
	{
		return static::createResponseByURL(URLResolver::resolve($urlName, $urlParam), $queryStringParam);
	}

	/**
	 * @param string $url
	 * @param array $urlParameter
	 *
	 * @return Response
	 */
	public static function createResponseByURL(string $url, array $urlParameter = []): Response
	{
		$response = (new Response())->withStatus(301);

		if (empty($urlParameter))
		{
			return $response->withAddedHeader('Location', $url);
		}

		$url .= '?';

		foreach ($urlParameter as $key => $value)
		{
			$url .= "{$key}={$value}";
		}

		return $response->withAddedHeader('Location', $url);
	}

}