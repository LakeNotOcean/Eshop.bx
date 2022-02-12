<?php

namespace Up\Core\DI;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;


class DIContainer
{
	private static $implementations = [
		'Up\Core\TemplateProcessor' => 'Up\Core\TemplateProcessorImpl',

		/*==========================
		Service
		==========================*/
		'Up\Service\ImageService\ImageServiceInterface' => 'Up\Service\ImageService\ImageService',
		'Up\Service\ItemService\ItemServiceInterface' => 'Up\Service\ItemService\ItemService',
		'Up\Service\SpecificationService\SpecificationsService' => 'Up\Service\SpecificationService\SpecificationsServiceImpl',
		'Up\Service\TagService\TagService' => 'Up\Service\TagService\TagServiceImpl',
		'Up\Service\UserService\UserService' => 'Up\Service\UserService\UserServiceImpl',

		/*==========================
		DAO
		==========================*/
		'Up\DAO\ItemDAO\ItemDAO' => 'Up\DAO\ItemDAO\ItemDAOmysql',
		'Up\DAO\SpecificationDAO\SpecificationDAO' => 'Up\DAO\SpecificationDAO\SpecificationDAOmysql',
		'Up\DAO\TagDAO\TagDAO' => 'Up\DAO\TagDAO\TagDAOmysql',
		'Up\DAO\UserDAO\UserDAO' => 'Up\DAO\UserDAO\UserDAOmysql',
	];

	/**
	 * @throws ReflectionException
	 */
	public static function get(string $name)
	{
		if (interface_exists($name))
		{
			$name = self::$implementations[$name];
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
			$params[] = self::get($parameter->getType()->getName());
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
