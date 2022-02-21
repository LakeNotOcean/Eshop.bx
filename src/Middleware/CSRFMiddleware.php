<?php

namespace Up\Middleware;

use Up\Core\Error\ValidationException;
use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;
use Up\Core\Router\Router;
use Up\Lib\CSRF\CSRF;
use Up\Lib\CSRF\Error\CSRFException;

class CSRFMiddleware extends AbstractMiddleware
{
	private const urlWhiteList = [];

	/**
	 * @throws CSRFException
	 * @throws NoSuchQueryParameterException
	 */
	public function __invoke(Request $request, ...$params): Response
	{
		if (!CSRF::tokenExist())
		{
			CSRF::generateToken();
		}
		if ($request->getMethod() !== 'POST')
		{
			return call_user_func($this->getResponse, $request, ...$params);
		}
		$urlName = $request->getRouteName();

		if (in_array($urlName, static::urlWhiteList, true))
		{
			return call_user_func($this->getResponse, $request, ...$params);
		}

		try
		{
			CSRF::validateToken($request->getPostParametersByName(CSRF::TOKEN_FIELD_NAME));
		}
		catch (ValidationException $e)
		{
			$expected = CSRF::getToken();
			$now = $request->getPostParametersByName('tokenFieldName');
			throw new CSRFException("CSRF token failed validation. Expected: {$expected}, now: {$now}");
		}
		catch (NoSuchQueryParameterException $e)
		{
			throw new CSRFException("For does not contains CSRF token field. Use CSRF::getFormField()");
		}
		return call_user_func($this->getResponse, $request, ...$params);
	}
}