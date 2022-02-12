<?php

namespace Up\Core\DI;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;


class DIContainer implements DIContainerInterface
{
	protected $implementations;

	public function __construct(DIConfigInterface $config)
	{
		$this->implementations = $config->getConfig();
	}

	/**
	 * @throws ReflectionException
	 */
	public function get(string $name): object
	{
		$class = $this->getImplementation($name);

		$methods = get_class_methods($class) ?? [];

		if (in_array('__construct', $methods, true))
		{
			$dependencies = $this->getConstructorDependencies($class);
			return (new ReflectionClass($class))->newInstanceArgs($dependencies);
		}

		if (in_array('getInstance', $methods, true))
		{
			return $class::getInstance();
		}

		return new $class();
	}

	protected function getImplementation(string $name)
	{
		if (interface_exists($name))
		{
			return $this->implementations[$name];
		}
		return $name;
	}

	/**
	 * @throws ReflectionException
	 */
	protected function getConstructorDependencies(string $class): array
	{
		$reflectionMethod = new ReflectionMethod($class, '__construct');
		$dependencies = [];
		foreach ($reflectionMethod->getParameters() as $parameter)
		{
			$dependencies[] = $this->get($parameter->getType()->getName());
		}
		return $dependencies;
	}

}
