<?php

namespace Up\Core\Enum;


abstract class Enum implements \JsonSerializable
{
	protected $value;
	private $key;
	protected static $cache = [];
	protected static $instances = [];

	public function __construct($value)
	{
		if ($value instanceof static)
		{
			$value = $value->getValue();
		}
		$this->key = static::assertValidValueReturningKey($value);
		$this->value = $value;
	}

	public function __wakeup()
	{
		if ($this->key === null)
		{
			$this->key = static::search($this->value);
		}
	}

	public static function from(string $value)
	{
		$key = static::assertValidValueReturningKey($value);
		return self::__callStatic($key, []);
	}

	public function getValue()
	{
		return $this->value;
	}

	public function getKey(): string
	{
		return $this->key;
	}

	public function __toString()
	{
		return (string)$this->value;
	}

	final public function equals($variable = null): bool
	{
		return $variable instanceof self
			&& $this->getValue() === $variable->getValue()
			&& static::class === \get_class($variable);
	}

	public static function keys(): array
	{
		return \array_keys(static::toArray());
	}

	public static function values(): array
	{
		$values = array();
		foreach (static::toArray() as $key => $value) {
			$values[$key] = new static($value);
		}
		return $values;
	}

	public static function toArray()
	{
		$class = static::class;

		if (!isset(static::$cache[$class])) {
			$reflection = new \ReflectionClass($class);
			static::$cache[$class] = $reflection->getConstants();
		}

		return static::$cache[$class];
	}

	public static function isValid($value): bool
	{
		return \in_array($value, static::toArray(), true);
	}

	public static function assertValidValue($value): void
	{
		self::assertValidValueReturningKey($value);
	}

	private static function assertValidValueReturningKey($value): string
	{
		if (false === ($key = static::search($value))) {
			throw new \UnexpectedValueException("Value '$value' is not part of the enum " . static::class);
		}

		return $key;
	}

	public static function isValidKey($key): bool
	{
		$array = static::toArray();

		return isset($array[$key]) || \array_key_exists($key, $array);
	}

	public static function search($value)
	{
		return \array_search($value, static::toArray(), true);
	}

	public static function __callStatic($name, $arguments)
	{
		$class = static::class;
		if (!isset(self::$instances[$class][$name]))
		{
			$array = static::toArray();
			if (!isset($array[$name]) && !\array_key_exists($name, $array))
			{
				$message = "No static method or enum constant '$name' in class " . static::class;
				throw new \BadMethodCallException($message);
			}
			return self::$instances[$class][$name] = new static($array[$name]);
		}
		return clone self::$instances[$class][$name];
	}

	public function jsonSerialize()
	{
		return $this->getValue();
	}

}
