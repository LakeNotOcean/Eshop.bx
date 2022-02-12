<?php

namespace Up\Core\DI;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;


class DIContainer implements DIContainerInterface
{
	protected $implementations;

	public function __construct(ImplementationsConfigInterface $implementations)
	{
		$this->implementations = $implementations->getImplementationsConfig();
	}

	/**
	 * @throws ReflectionException
	 */
	public function get(string $class): object
	{
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

	/**
	 * @throws ReflectionException
	 */
	protected function getConstructorDependencies(string $class): array
	{
		$reflectionMethod = new ReflectionMethod($class, '__construct');
		$dependencies = [];
		foreach ($reflectionMethod->getParameters() as $parameter)
		{
			$dependency = $this->getDependency($class, $parameter->getType()->getName());
			$dependencies[] = $this->get($dependency);
		}
		return $dependencies;
	}

	protected function getDependency(string $class, string $parameter): string
	{
		return $this->implementations[$class][$parameter];
	}

}
