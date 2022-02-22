<?php

namespace Up\Core\Router;


use Up\Lib\URLHelper;

class Router
{
	private static $instance;
	protected $routes = [];
	private $typeToRegex = [
		'int' => '\-?[1-9][0-9]*',
		'positiveInt' => '[1-9][0-9]*',
		'string' => '[\w\-\_]+',
		'slug' => '[a-z0-9]+(?:-[a-z0-9]+)*',
	];

	private function __construct()
	{
	}

	public static function getInstance(): self
	{
		if (null === self::$instance)
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get(string $urlTemplate, array $callback, string $name): void
	{
		$this->register('GET', $urlTemplate, $callback, $name);
	}

	/**
	 * Adds new route to the router.
	 *
	 * @param string $method HTTP request method (GET|POST|PUT|DELETE).
	 * @param string $urlTemplate URL template with parameters placeholders, like (/user/:user_id).
	 * @param array $callback Handler for this route.
	 * @param string $name name of the route.
	 *
	 * @return void
	 */
	public function register(string $method, string $urlTemplate, array $callback, string $name): void
	{
		$key = $this->getUrlPatternWithoutTemplate($urlTemplate);
		if (array_key_exists($key, $this->routes) && $this->routes[$key][0]['name'] !== $name)
		{
			throw new \InvalidArgumentException('Dont create routes with equal url, but with different names');
		}
		$this->routes[$key][] = [
			'method' => $method,
			'urlTemplate' => $urlTemplate,
			'urlRegex' => $this->makeRegexFromUrl($urlTemplate),
			'callback' => $callback,
			'name' => $name,
		];
	}

	/**
	 * @param string $urlTemplate
	 *
	 * @return string
	 */
	public function makeRegexFromUrl(string $urlTemplate): ?string
	{
		$regexBody = preg_replace_callback(
			'/\\\{(?<type>[\da-zA-Z]+)\\\:(?<variableName>-?[\da-zA-Z]+)\\\}/',
			[$this, 'createRegexCaptureGroup'],
			preg_quote($urlTemplate, null)
		);

		return is_null($regexBody) ? null : "#^" . $regexBody . "$#D";
	}

	public function post(string $urlTemplate, array $callback, string $name): void
	{
		$this->register('POST', $urlTemplate, $callback, $name);
	}

	public function put(string $urlTemplate, array $callback, string $name): void
	{
		$this->register('PUT', $urlTemplate, $callback, $name);
	}

	public function delete(string $urlTemplate, array $callback, string $name): void
	{
		$this->register('DELETE', $urlTemplate, $callback, $name);
	}

	public function getUrlTemplateByName(string $name): ?string
	{
		foreach ($this->routes as $routesWithEqualURL)
		{
			foreach ($routesWithEqualURL as $route)
			{
				if ($route['name'] === $name)
				{
					return $route['urlTemplate'];
				}
			}
		}

		return null;
	}

	/**
	 * @throws Error\RoutingException
	 */
	public function route(string $method, string $path): array
	{
		$path = URLHelper::removeIfExistGetParametersFromPath($path);
		foreach ($this->routes as $routesWithEqualURL)
		{
			foreach ($routesWithEqualURL as $route)
			{
				$matches = [];
				if ($method === $route['method'] && preg_match($route['urlRegex'], $path, $matches))
				{
					return [
						'callback' => $route['callback'],
						'params' => $matches,
					];
				}
			}
		}
		throw new Error\RoutingException("Не найдена ручка соответствующая пути {$path} и методу {$method}");
	}

	/**
	 * @throws Error\RoutingException
	 */
	public function getRouteName(string $url)
	{
		$path = URLHelper::removeIfExistGetParametersFromPath($url);
		foreach ($this->routes as $routesWithEqualURL)
		{
			foreach ($routesWithEqualURL as $route)
			{
				$matches = [];
				if (preg_match($route['urlRegex'], $path, $matches))
				{
					return $route['name'];
				}
			}
		}
		throw new Error\RoutingException("Не найдена ручка соответствующая имени {$route['name']}");
	}

	/**
	 * @throws Error\URLParameterTypeException
	 */
	private function createRegexCaptureGroup($params): string
	{
		$type = $params['type'];
		$variableName = $params['variableName'];
		if (!isset($this->typeToRegex[$type]))
		{
			throw new Error\URLParameterTypeException('Указан неверный тип переменной урла');
		}

		return '(?<' . $variableName . '>' . $this->typeToRegex[$type] . ')';
	}

	private function getUrlPatternWithoutTemplate(string $urlTemplate)
	{
		return preg_replace('/{\w+:\w+}/', '', $urlTemplate);
	}
}
