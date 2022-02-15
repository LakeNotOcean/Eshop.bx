<?php

namespace Up\Core\Enum;

use Exception;
use ReflectionClass;
use ReflectionException;

abstract class Enum
{

	protected $value;

	private $key;

	private static $constArray;

	private static $instances;

	/**
	 * @throws ReflectionException
	 * @throws EnumException
	 */
	public function __construct(string $value)
	{
		$this->key = self::getKeyByValue($value);
		$this->value = $value;
	}

	/**
	 * @throws EnumException|ReflectionException
	 */
	public static function __callStatic(string $key, $arguments): Enum
	{

		if (!self::isValidKey($key))
		{
			throw new EnumException('key does not exists in ' . get_called_class());
		}
		$className = get_called_class();
		$value=self::getValueByKey($key);
		if (!isset(self::$instances[$className][$key]))
		{
			self::$instances[$className][$key] = new static($value);
		}

		return clone self::$instances[$className][$key];
	}

	/**
	 * @throws ReflectionException
	 * @throws EnumException
	 */
	public static function from($value): self
	{
		$key = self::getKeyByValue($value);

		return self::__callStatic($key, []);
	}

	public function equals(Enum $obj): bool
	{
		return $this->getValue() === $obj->getValue();
	}

	public static function isValidValue($value): bool
	{
		try
		{
			self::getKeyByValue($value);
		}
		catch (Exception $ex)
		{
			return false;
		}

		return true;
	}

	/**
	 * @throws ReflectionException
	 */
	public static function isValidKey($key): bool
	{
		$constants = self::getConstants();
		if (!array_key_exists($key, $constants))
		{
			return false;
		}

		return true;

	}

	/**
	 * @throws ReflectionException
	 */
	public static function getKeys(): array
	{
		$constants = self::getConstants();

		return array_keys($constants);
	}

	/**
	 * @throws ReflectionException
	 */
	public static function getValues(): array
	{
		$constants = self::getConstants();

		return array_values($constants);
	}

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
	private static function getKeyByValue(string $value): string
	{
		$constants = self::getConstants();
		$key = array_search($value, $constants);
		if (!is_string($key))
		{
			throw new EnumException('this value is not in ' . get_called_class());
		}

		return $key;
	}

	/**
	 * @throws EnumException
	 * @throws ReflectionException
	 */
	private static function getValueByKey(string $key):string
	{
		$constants = self::getConstants();
		$value=$constants[$key];
		if (!is_string($key))
		{
			throw new EnumException('this key is not in ' . get_called_class());
		}
		return $value;
	}


	//
	// /**
	//  * @throws EnumException
	//  * @throws ReflectionException
	//  */
	// public static function isValidName(string $name, bool $strict = false): void
	// {
	// 	$constants = self::getConstants();
	//
	// 	$keys = $strict ? $constants : array_map('strtolower', array_keys($constants));
	//
	// 	if (!in_array(strtolower($name), $keys))
	// 	{
	// 		throw  new EnumException('invalid name');
	// 	}
	// }
	//
	// /**
	//  * @throws ReflectionException
	//  * @throws EnumException
	//  */
	// public static function isValidValue(int $value, bool $strict = true): void
	// {
	// 	$values = array_values(self::getConstants());
	//
	// 	if (!in_array($value, $values, $strict))
	// 	{
	// 		throw new EnumException('invalid value');
	// 	}
	// }
	//
	// /**
	//  * @throws ReflectionException
	//  * @throws EnumException
	//  */
	// public static function fromString($name): int
	// {
	// 	self::isValidName($name, true);
	// 	$constants = self::getConstants();
	//
	// 	return $constants[$name];
	//
	// }
	//
	// /**
	//  * @throws ReflectionException
	//  * @throws EnumException
	//  */
	// public static function toString($value): string
	// {
	// 	self::isValidValue($value, true);
	//
	// 	return array_search($value, self::getConstants());
	// }
	public function getValue(): string
	{
		return $this->value;
	}

	public function getKey(): string
	{
		return $this->key;
	}
}