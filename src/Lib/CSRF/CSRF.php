<?php

namespace Up\Lib\CSRF;

use Up\Core\Error\ValidationException;

class CSRF
{
	private const tokenSessionKey = 'CSRF';
	public const tokenFieldName = 'csrf_token';

	public static function generateToken()
	{
		$_SESSION[static::tokenSessionKey] = static::generateTokenString();
	}

	public static function tokenExist(): bool
	{
		return isset($_SESSION[static::tokenSessionKey]);
	}

	public static function getFormField()
	{
		if (!static::tokenExist())
		{
			static::generateToken();
		}
		$fieldName = static::tokenFieldName;
		$token = static::getToken();
		return "<input name=\"{$fieldName}\" type=\"hidden\" value=\"{$token}\" >";
	}

	public static function getToken()
	{
		return $_SESSION[static::tokenSessionKey];
	}

	/**
	 * @throws ValidationException
	 */
	public static function validateToken(string $token): bool
	{
		if (!isset($_SESSION[static::tokenSessionKey]))
		{
			throw new ValidationException('CSRF token ');
		}

		return $token === static::getToken();
	}

	public static function destroyToken()
	{
		if (static::tokenExist())
		{
			unset($_SESSION[static::tokenSessionKey]);
		}
	}

	private static function generateTokenString(): string
	{
		return bin2hex(random_bytes(40));
	}
}