<?php

namespace Up\Core;
use Up\Core\Message\Response;

class Application
{

	public static function run(): bool
	{
		$mode = 'Dev';
		$result = false;


		if ($mode === 'Dev')
		{
			Migration\MigrationManager::run();
		}

		$router = Router\Router::getInstance();
		$response = new Response();
		try
		{
			$callback = $router->route($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
		}
		catch (Router\Errors\RoutingException $e)
		{
			$response->withStatus(418);
			//todo залогировали
			$result = false;
			return $result;
		}

		$params = $callback['params'];



		return false;
	}


}