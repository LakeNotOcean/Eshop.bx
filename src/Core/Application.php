<?php

namespace Up\Core;

use Exception;
use MigrationException;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;
use Up\Core\Database\DefaultDatabase;
use Up\Core\DI\Container;
use Up\Core\DI\DIConfigPHP;
use Up\Core\DI\Error\DIException;
use Up\Core\Logger\Logger;
use Up\Core\Message\Request;
use Up\Core\Migration\MigrationManager;
use Up\Core\Router\Error\RoutingException;
use Up\Core\Router\Router;
use Up\Core\Settings\Settings;


class Application
{

	/**
	 * @throws Exception
	 */
	public static function run(): bool
	{
		$logger = new Logger();
		$settings = Settings::getInstance();
		//Прописывание маршрутов
		/** @var Router $router */
		include '../src/routes.php';

		$container = new Container(
			new DIConfigPHP($settings->getSettings('DIConfigPath'))
		);

		############### БЛОК ПРИМЕНЕНИЯ МИГРАЦИЙ ######################
		$isDev = $settings->getSettings('isDev');
		if ($isDev === true)
		{
			$migration = new MigrationManager(DefaultDatabase::getInstance());
			try
			{
				$migration->updateDatabase();
			}
			catch (MigrationException $e)
			{
				$logger->log('info',$e);
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
			$logger->log('info',$e);
			return false;
		}

		$params = $method['params'];
		$params['request'] = Request::createFromGlobals();

		try
		{
			$controller = $container->get($method['callback'][0]);
		}
		catch (ReflectionException|DIException $e)
		{
			$logger->log('info', $e);
		}
		catch (Exception $exception)
		{
			$logger->log('info', $exception);
		}

		try
		{
			$callbackReflection = new ReflectionMethod($controller, $method['callback'][1]);
		}
		catch (ReflectionException $e)
		{
			//todo вызов отдельного контроллера ошибок
			$logger->log('info',$e);
			return false;
		}

		$args = [];
		foreach ($callbackReflection->getParameters() as $parameter)
		{
			if (isset($params[$parameter->getName()]))
			{
				$args[] = $params[$parameter->getName()];
			}
			elseif (!$parameter->isDefaultValueAvailable())
			{
				throw new RuntimeException("No value for parameter $" . $parameter->getName());
			}
		}
		try
		{
			$response = $callbackReflection->invokeArgs($controller, $args);
		}
		catch (Exception $e)
		{
			$logger->log('info',$e);
			return false;
		}

		$response->flush();
		$logger->log('notice','Посещение страницы {domain}{url}',['domain' => $settings->getSettings('domainName'),'url' => $_SERVER['REQUEST_URI']]);
		return true;
	}
}
