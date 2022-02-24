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
use Up\Core\Logger\Application\ApplicationLogger;
use Up\Core\Message\Request;
use Up\Core\Middleware\MiddlewareManager;
use Up\Core\Migration\MigrationManager;
use Up\Core\Router\Error\RoutingException;
use Up\Core\Router\Router;
use Up\Core\Settings\Settings;
use Up\Lib\Redirect;

class Application
{

	/**
	 * @throws Exception
	 */
	public static function run(): bool
	{
		$logger = ApplicationLogger::getLogger();
		$settings = Settings::getInstance();
		$request = Request::createFromGlobals();

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
				$logger->info($e);
			}
		}
		############################################################

		try
		{
			$method = $router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
		}
		catch (RoutingException $e)
		{
			Redirect::createResponseByURLName('404')->flush();

			//todo вызов отдельного контроллера ошибок
			$logger->info($e, ['url' => $request->getRequestUrl(), 'method' => $request->getMethod(), 'cookie' => $request->getCookies()]);

			return false;
		}

		$params = $method['params'];
		$params['request'] = $request;


		$container = DIContainer::getInstance();
		try
		{
			$controller = $container->get($method['callback'][0]);
			$callbackReflection = new ReflectionMethod($controller, $method['callback'][1]);
		}
		catch (ReflectionException $e)
		{
			//todo вызов отдельного контроллера ошибок
			$logger->info($e);

			return false;
		}
		catch (Exception $exception)
		{
			$a = 1;
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
		$response = $middlewareManager->invokeWithMiddleware([$controller, $method['callback'][1]], ...$args);
		$response->flush();
		$logger->notice(
					 'Посещение страницы',
					 ['domain' => $settings->getSettings('domainName'), 'url' => $_SERVER['REQUEST_URI']]);

		return true;
	}
}
