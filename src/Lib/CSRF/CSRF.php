<?php

namespace Up\Lib\CSRF;

use Up\Core\Error\ValidationException;

class CSRF
{
	private const TOKEN_SESSION_KEY = 'CSRF';
	public const TOKEN_FIELD_NAME = 'csrf_token';

	public static function generateToken(): void
	{
		$_SESSION[static::TOKEN_SESSION_KEY] = static::generateTokenString();
	}

	public static function tokenExist(): bool
	{
		return isset($_SESSION[static::TOKEN_SESSION_KEY]);
	}

	public static function getFormField(): string
	{
		if (!static::tokenExist())
		{
			static::generateToken();
		}
		$fieldName = static::TOKEN_FIELD_NAME;
		$token = static::getToken();
		return "<input name=\"{$fieldName}\" type=\"hidden\" value=\"{$token}\" class=\"token\">";
	}

	public static function getToken()
	{
		return $_SESSION[static::TOKEN_SESSION_KEY];
	}

	/**
	 * @throws ValidationException
	 */
	public static function validateToken(string $token): bool
	{
		if (!isset($_SESSION[static::TOKEN_SESSION_KEY]))
		{
			throw new ValidationException('CSRF token ');
		}

		return $token === static::getToken();
	}

	public static function destroyToken(): void
	{
		if (static::tokenExist())
		{
			unset($_SESSION[static::TOKEN_SESSION_KEY]);
		}
	}

	/**
	 * @throws \Exception
	 */
	private static function generateTokenString(): string
	{
		return bin2hex(random_bytes(40));
	}
}