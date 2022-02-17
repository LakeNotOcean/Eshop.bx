<?php

namespace Up\Lib;

use Up\Core\Message\Response;
use Up\Core\Router\Router;
use Up\Core\Router\URLResolver;

class Redirect
{
	/**
	 * @param string $urlName
	 * @param array $urlParameter Тут передаются get параметры
	 *
	 * @return Response
	 * @throws \Up\Core\Router\Error\ResolveException
	 */
	public static function createResponse(string $urlName, array $urlParameter = []): Response
	{
		$response = new Response();
		$url =  URLResolver::resolve($urlName);

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