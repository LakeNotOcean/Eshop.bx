<?php

namespace Up\Core\DI;

use Up\Core\DI\Error\DIException;

class Container
{
	protected $services = [];
	protected $config = [];

	public function __construct(DIConfigInterface $config)
	{
		$this->config = $config->getConfig('');
	}

	/**
	 * @throws DIException
	 */
	public function get(string $name)
	{
		if(isset($this->service[$name]))
		{
			return $this->service[$name];
		}
		if(!isset($this->config[$name]))
		{
			throw new DIException('Wrong config. Not found: ' . $name);
		}
		$args = [];
		foreach ($this->config[$name]['initArgs'] as $arg)
		{
			if($arg[0] == 'class')
			{
				$args[] = $this->get($arg[1]);
			}
			if($arg[0] == 'var')
			{
				$args[] = $arg[1];
			}
		}
		$service = null;
		if($this->config[$name]['initType'] == 'constructor')
		{
			$service = (new \ReflectionClass($this->config[$name]['classPath']))->newInstanceArgs($args);
		}
		if($this->config[$name]['initType'] == 'singleton')
		{
			$service = (new \ReflectionMethod($this->config[$name]['classPath'],
											 $this->config[$name]['initMethod'])
			)->invokeArgs(null, $args);
		}
		$this->services[$name] = $service;
		return $service;
	}

	public function has(string $name): bool
	{
		return isset($this->services[$name]);
	}
}