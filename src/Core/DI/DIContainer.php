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
	public function get(string $name)
	{
		if (interface_exists($name))
		{
			$name = $this->implementations[$name];
		}
		$methods = get_class_methods($name) ?? [];
		if (in_array('__construct', $methods, true))
		{
			$reflection = new ReflectionMethod($name, '__construct');
		}
		elseif (in_array('getInstance', $methods, true))
		{
			$reflection = new ReflectionMethod($name, 'getInstance');
		}
		else
		{
			return new $name();
		}

		$params = [];
		foreach ($reflection->getParameters() as $parameter)
		{
			$params[] = $this->get($parameter->getType()->getName());
		}
		$reflector = new ReflectionClass($name);
		if (in_array('__construct', $methods, true))
		{
			$class = $reflector->newInstanceArgs($params);
		}
		elseif (in_array('getInstance', $methods, true))
		{
			$class = $name::getInstance();
		}
		return $class;
	}

}
