<?php

namespace Up\Core;

use Exception;
use MigrationException;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;
use Up\Controller\CatalogController;
use Up\Core\DataBase\DefaultDatabase;
use Up\Core\Migration\MigrationManager;
use Up\Core\Router\Errors\RoutingException;
use Up\Core\Router\Router;
use Up\DAO\ItemDAOmysql;
use Up\Service\CatalogServiceImpl;

class Application
{

	public static function run(): bool
	{
		###############БЛОК ИНИЦИАЛИЗАЦИЙ############################
		$templateProcessor = new TemplateProcessorImpl();
		$itemDAO = new ItemDAOmysql(DefaultDatabase::getInstance());
		$catalogService = new CatalogServiceImpl($itemDAO);
		$catalogController = new CatalogController($templateProcessor, $catalogService);
		#############################################################

		//Прописывание маршрутов
		//include '../src/routes.php'; т.к теперь контроллеры это обычные объекты, а не статические, то для
		//                             прописывания маршрутов нужны их экземпляры. Таким образом,
		//                             прописывание маршрутов должно делаться в run()

		###############БЛОК ПРОПИСЫВАНИЯ МАРШРУТОВ###################
		$router = Router::getInstance();
		$router->get('/', [$catalogController, 'getItems'], 'home');
		#############################################################

		###############БЛОК ПРИМЕНЕНИЯ МИГРАЦИЙ######################
		$settings = Settings::getInstance();
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
			//$response->withStatus(418);
			//$response->flush();
			//todo залогировали
			return false;
		}

		$params = $method['params'];

		if (is_array($method['callback']))
		{
			try
			{
				$callbackReflection = new ReflectionMethod($method['callback'][0], $method['callback'][1]);
			}
			catch (ReflectionException $e)
			{
				//todo вызов отдельного контроллера ошибок
				//$response->withStatus(997);
				//$response->flush();
				//todo залогировали
				return false;
			}
		}
		elseif (is_string($method['callback']) || is_callable($method['callback']))
		{
			try
			{
				$callbackReflection = new ReflectionFunction($method['callback']);
			}
			catch (ReflectionException $e)
			{
				//todo вызов отдельного контроллера ошибок
				//$response->withStatus(999);
				//$response->flush();
				//todo залогировали
				return false;
			}
		}
		else
		{
			//todo вызов отдельного контроллера ошибок
			//$response->withStatus(998);
			//$response->flush();
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
			$response = call_user_func_array($method['callback'], $args);
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