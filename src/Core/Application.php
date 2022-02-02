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
		$response = new Response();

		try
		{
			$callback = $router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
		}

		catch (Router\Errors\RoutingException $e)
		{
			$response->withStatus(418);
			$response->flush();
			//todo залогировали
			return false;
		}

		$params = $callback['params'];

		if (is_array($callback['callback']))
		{
			try
			{
				$callbackReflection = new \ReflectionMethod($callback['callback'][0], $callback['callback'][1]);
			}
			catch (\ReflectionException $e)
			{
				$response->withStatus(997);
				$response->flush();
				//todo залогировали
				return false;
			}
		}
		else if (is_string($callback['callback']) || is_callable($callback['callback']))
		{
			try
			{
				$callbackReflection = new \ReflectionFunction($callback['callback']);
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
			$result = call_user_func_array($callback['callback'], $args);
		}
		catch (Exception $e)
		{
			// TODO: журналируем и выводим исключение
			return false;
		}
		if ($result instanceof Response)
		{
			$result->flush();
			return true;
		}
		if (is_array($result))
		{
			$response->withBodyJSON($result);
			$response->flush();
			return true;
		}
		if (is_string($result))
		{
			$response->withBodyHTML($result);
			$response->flush();
			return true;
		}
		$response->withStatus(418);
		$response->flush();
		return false;
	}


}