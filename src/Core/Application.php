<?php

namespace Up\Core;

use Exception;
use MigrationException;
use ReflectionException;
use ReflectionMethod;
use RuntimeException;
use Throwable;
use Up\Core\Database\DefaultDatabase;
use Up\Core\DI\DIContainer;
use Up\Core\Logger\Logger;
use Up\Core\Message\Request;
use Up\Core\Middleware\MiddlewareManager;
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
				$logger->log('info', $e);
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
			$logger->log('info', $e);

			return false;
		}

		$params = $method['params'];
		$params['request'] = Request::createFromGlobals();


		$container = DIContainer::getInstance();
		try
		{
			$controller = $container->get($method['callback'][0]);
			$callbackReflection = new ReflectionMethod($controller, $method['callback'][1]);
		}
		catch (ReflectionException $e)
		{
			//todo вызов отдельного контроллера ошибок
			$logger->log('info', $e);

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

		$middlewareManager = MiddlewareManager::getInstance();
		$middlewareManager->loadMiddlewares();
		try
		{
				$response = $middlewareManager->invokeWithMiddleware([$controller, $method['callback'][1]], ...$args);
		}
		catch (Throwable $e)
		{
			$logger->log('info', $e);

			return false;
		}

		$response->flush();
		$logger->log('notice',
					 'Посещение страницы {domain}{url}',
					 ['domain' => $settings->getSettings('domainName'), 'url' => $_SERVER['REQUEST_URI']]);

		return true;
	}
}
