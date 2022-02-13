<?php

namespace Up\Core\DI;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use Up\Core\PHPDocParser\PHPDocParser;


class DIContainer implements DIContainerInterface
{
	private static $instance;
	protected $container;
	protected $phpDocParser;

	protected function __construct()
	{
		$this->container = [];
		$this->phpDocParser = PHPDocParser::getInstance();
	}

	public static function getInstance(): DIContainer
	{
		if (!isset(static::$instance))
		{
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * @throws ReflectionException
	 */
	public function get(string $class): object
	{
		$reflector = new ReflectionClass($class);

		$this->phpDocParser->setDocComment($reflector->getDocComment());

		$initMethod = $this->phpDocParser->get('@initMethod');
		if (!empty($initMethod))
		{
			$dependencies = $this->getDependencies($class, $initMethod);
			return (new ReflectionMethod($class, $initMethod))->invokeArgs(null, $dependencies);
		}

		$methods = get_class_methods($class) ?? [];
		if (in_array('__construct', $methods, true))
		{
			$dependencies = $this->getDependencies($class, '__construct');
			return $reflector->newInstanceArgs($dependencies);
		}

		return new $class();
	}

	/**
	 * @throws ReflectionException
	 */
	protected function getDependencies(string $class, string $initMethod): array
	{
		$reflectionMethod = new ReflectionMethod($class, $initMethod);

		$this->phpDocParser->setDocComment($reflectionMethod->getDocComment());

		$dependencies = [];
		foreach ($this->phpDocParser->getList('@param') as $dependencyName)
		{
			if (isset($this->container[$dependencyName]))
			{
				$dependency = $this->container[$dependencyName];
			}
			else
			{
				$dependency = $this->get($dependencyName);
				$this->container[$dependencyName] = $dependency;
			}
			$dependencies[] = $dependency;
		}
		return $dependencies;
	}

}
