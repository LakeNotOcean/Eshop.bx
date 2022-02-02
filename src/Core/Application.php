<?php



namespace Up\Core;
use Exception;
use Up\Core\Message\Response;
use Up\Core\Migration\MigrationManager;

class Application
{

	public static function run(): bool
	{
		//Применение миграции если включен режим разработчика
		$settings = Settings::getInstance();
		$isDev = $settings->isDev();
		if ($isDev === true)
		{
			$dataBase = DataBaseConnect::getInstance();
			$migrationManager = new MigrationManager($dataBase->getDataBase());
			$migrationManager->updateDatabase();
		}

		//Инициализация роутера

		$router = Router\Router::getInstance();
		$router->get('/home',[\Up\Controller\CatalogController::class, 'getItems'],'/home');
		$response = new Response();

		try
		{
			$method = $router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
		}

		catch (Router\Errors\RoutingException $e)
		{
			$response->withStatus(418);
			$response->flush();
			//todo залогировали
			return false;
		}

		$params = $method['params'];

		if (is_array($method['callback']))
		{
			try
			{
				$callbackReflection = new \ReflectionMethod($method['callback'][0], $method['callback'][1]);
			}
			catch (\ReflectionException $e)
			{
				$response->withStatus(997);
				$response->flush();
				//todo залогировали
				return false;
			}
		}
		else if (is_string($method['callback']) || is_callable($method['callback']))
		{
			try
			{
				$callbackReflection = new \ReflectionFunction($method['callback']);
			}
			catch (\ReflectionException $e)
			{
				$response->withStatus(999);
				$response->flush();
				//todo залогировали
				return false;
			}
		}
		else
		{
			$response->withStatus(998);
			$response->flush();
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