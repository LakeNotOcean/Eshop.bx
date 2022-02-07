<?php

namespace Up\Core;

use Exception;
use MigrationException;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use Up\Controller\CatalogController;
use Up\Core\Database\DefaultDatabase;
use Up\Core\DI\Error\DIException;
use Up\Core\Message\Request;
use Up\Core\Migration\MigrationManager;
use Up\Core\Router\Errors\RoutingException;
use Up\Core\Router\Router;
use Up\DAO\ItemDAOmysql;
use Up\Service\CatalogServiceImpl;

class Application
{

	public static function run(): bool
	{
		$settings = Settings::getInstance();
		//Прописывание маршрутов
		/** @var Router $router */
		include '../src/routes.php';

		$container = new \Up\Core\DI\Container(
			new \Up\Core\DI\DIConfigPHP($settings->getDIConfigPath())
		);

		###############БЛОК ПРИМЕНЕНИЯ МИГРАЦИЙ######################

		$isDev = $settings->isDev();
		if ($isDev === true)
		{
			$migration = new MigrationManager(DefaultDatabase::getInstance());
			try
			{
				$migration->updateDatabase();
			}
			catch (MigrationException $e)
			{
				//todo Залогировали
				var_dump('Миграция не удалась!');
			}
		}
		############################################################

		try
		{
			$method = $router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
		}
		catch (RoutingException $e)
		{
			//todo вызов отдельного контроллера ошибок
			//todo залогировали
			return false;
		}

		$params = $method['params'];
		$params['request'] = Request::createFromGlobals();

		try
		{
			$controller = $container->get($method['callback'][0]);
		}
		catch (ReflectionException $e)
		{
		}
		catch (DIException $e)
		{
		}

		try
		{
			$callbackReflection = new ReflectionMethod($controller, $method['callback'][1]);
		}
		catch (ReflectionException $e)
		{
			//todo вызов отдельного контроллера ошибок
			//todo залогировали
			return false;
		}

		$args = [];
		foreach ($callbackReflection->getParameters() as $parameter)
		{
			if (isset($params[$parameter->getName()]))
			{
				$args[] = $params[$parameter->getName()];
			}
			else
			{
				if (!$parameter->isDefaultValueAvailable())
				{
					throw new Exception("No value for parameter $" . $parameter->getName());
				}
			}
		}
		try
		{
			$response = $callbackReflection->invokeArgs($controller ,$args);
		}
		catch (Exception $e)
		{
			// TODO: журналируем и выводим исключение
			return false;
		}

		$response->flush();

		return true;
	}
}