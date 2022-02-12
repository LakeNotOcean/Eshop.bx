<?php

namespace Up\Core\DI;

use http\Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;


class DIContainer implements DIContainerInterface
{
	protected $implementations;
	protected $singletons;

	public function __construct(DIConfigInterface $config)
	{
		$this->implementations = $config->getImplementations();
		$this->singletons = $config->getSingletons();
	}

	/**
	 * @throws ReflectionException
	 */
	public function get(string $class): object
	{
		if (array_key_exists($class, $this->singletons))
		{
			$initMethod = $this->singletons[$class];
			$dependencies = $this->getDependencies($class, $initMethod);
			return (new ReflectionMethod($class, $initMethod))->invokeArgs(null, $dependencies);
		}

		$methods = get_class_methods($class) ?? [];
		if (in_array('__construct', $methods, true))
		{
			$dependencies = $this->getDependencies($class, '__construct');
			return (new ReflectionClass($class))->newInstanceArgs($dependencies);
		}

		return new $class();
	}

	/**
	 * @throws ReflectionException
	 */
	protected function getDependencies(string $class, string $initMethod): array
	{
		$reflectionMethod = new ReflectionMethod($class, $initMethod);
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
