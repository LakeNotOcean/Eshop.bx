<?php

namespace Up\Core\Enum;

use ReflectionClass;
use ReflectionException;

abstract class Enum
{
	private static $constArray;

	/**
	 * @throws ReflectionException
	 */
	private static function getConstants(): array
	{
		if (!isset(self::$constArray))
		{
			self::$constArray = [];
		}
		$calledClass = get_called_class();
		if (!array_key_exists($calledClass, self::$constArray))
		{
			$reflect = new ReflectionClass($calledClass);
			self::$constArray[$calledClass] = $reflect->getConstants();
		}

		return self::$constArray[$calledClass];
	}

	/**
	 * @throws EnumException
	 * @throws ReflectionException
	 */
	public static function isValidName(string $name, bool $strict = false): void
	{
		$constants = self::getConstants();

		$keys = $strict ? $constants : array_map('strtolower', array_keys($constants));

		if (!in_array(strtolower($name), $keys))
		{
			throw  new EnumException('invalid name');
		}
	}

	/**
	 * @throws ReflectionException
	 * @throws EnumException
	 */
	public static function isValidValue(int $value, bool $strict = true): void
	{
		$values = array_values(self::getConstants());

		if (!in_array($value, $values, $strict))
		{
			throw new EnumException('invalid value');
		}
	}

	/**
	 * @throws ReflectionException
	 * @throws EnumException
	 */
	public static function fromString($name): int
	{
		self::isValidName($name, true);
		$constants = self::getConstants();

		return $constants[$name];

	}

	/**
	 * @throws ReflectionException
	 * @throws EnumException
	 */
	public static function toString($value): string
	{
		self::isValidValue($value, true);

		return array_search($value, self::getConstants());
	}
}