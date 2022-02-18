<?php

namespace Up\Lib;

use Up\Core\Message\Response;
use Up\Core\Router\Error\ResolveException;
use Up\Core\Router\URLResolver;

class Redirect
{
	/**
	 * @param string $urlName
	 * @param array $urlParameter Тут передаются get параметры
	 *
	 * @return Response
	 * @throws ResolveException
	 */
	public static function createResponseByURLName(string $urlName, array $urlParameter = []): Response
	{
		return static::createResponseByURL(URLResolver::resolve($urlName), $urlParameter);
	}

	/**
	 * @param string $url
	 * @param array $urlParameter
	 *
	 * @return Response
	 */
	public static function createResponseByURL(string $url, array $urlParameter = []): Response
	{
		$response = new Response();

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