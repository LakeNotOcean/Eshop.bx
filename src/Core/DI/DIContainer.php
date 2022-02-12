<?php

namespace Up\Core\DI;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;


class DIContainer implements DIContainerInterface
{
	private static $instance;
	protected $implementations;
	protected $singletons;
	protected $container;

	protected function __construct(DIConfigInterface $config)
	{
		$this->implementations = $config->getImplementations();
		$this->singletons = $config->getSingletons();
		$this->container = [];
	}

	public static function getInstance(DIConfigInterface $config): DIContainer
	{
		if (!isset(static::$instance))
		{
			static::$instance = new static($config);
		}
		return static::$instance;
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
			$dependencyName = $this->getDependency($class, $parameter->getType()->getName());
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

	protected function getDependency(string $class, string $parameter): string
	{
		return $this->implementations[$class][$parameter];
	}

}
