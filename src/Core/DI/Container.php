<?php

namespace Up\Core\DI;

use Up\Core\DI\Error\DIException;

class Container implements ContainerInterface
{
	protected $services = [];
	protected $config = [];

	public function __construct(DIConfigInterface $config)
	{
		$this->config = $config->getConfig();
	}

	/**
	 * @throws DIException|\ReflectionException
	 */
	public function get(string $name)
	{
		if (isset($this->service[$name]))
		{
			return $this->service[$name];
		}
		if (!isset($this->config[$name]))
		{
			throw new DIException('Wrong config. Not found: ' . $name);
		}
		$args = [];
		foreach ($this->config[$name]['initArgs'] as $arg)
		{
			switch ($arg[0])
			{
				case 'class':
					$args[] = $this->get($arg[1]);
					break;
				case 'var':
					$args[] = $arg[1];
					break;
				default:
					throw new DIException(
						'Wrong type of dependencies in '
						. $name
						. ' class. '
						. $arg[1]
						. ' should be only \'var\' or \'class\''
					);
			}
		}

		if (!isset($this->config[$name]['initType']))
		{
			throw new DIException('Missed initType in ' . $name . ' class');
		}

		switch ($this->config[$name]['initType'])
		{
			case 'constructor':
				$reflectionClass = new \ReflectionClass($this->config[$name]['classPath']);
				$service = $reflectionClass->newInstanceArgs($args);
				break;
			case 'singleton':
				$reflectionMethod = new \ReflectionMethod(
					$this->config[$name]['classPath'], $this->config[$name]['initMethod']
				);
				$service = $reflectionMethod->invokeArgs(null, $args);
				break;
			default:
				throw new DIException('Wrong init type in ' . $name . ' class');
		}
		$this->services[$name] = $service;

		return $service;
	}

	public function has(string $name): bool
	{
		return isset($this->services[$name]);
	}
}