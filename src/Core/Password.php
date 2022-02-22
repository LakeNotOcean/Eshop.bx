<?php

namespace Up\Core;

use InvalidArgumentException;

class Password
{
	public static function hashPassword(string $password): string
	{
		$hash = password_hash($password, PASSWORD_BCRYPT);
		if (!is_string($hash))
		{
			throw new InvalidArgumentException("Failed to hash the password: {$password}");
		}

		return $hash;
	}

	public static function verifyPassword(string $password, string $hash): bool
	{
		return password_verify($password, $hash);
	}
}