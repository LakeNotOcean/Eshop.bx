<?php

namespace Up\Core\Message;

use Up\Entity\User\User;
use Up\Entity\User\UserEnum;

/**
 * @initMethod createFromGlobals
 */
class Request
{
	private static $instance;

	private $queries = [];
	private $post = [];
	private $cookies = [];
	private $files = [];
	private $method = '';
	private $headers = [];
	private $requestUrl = '';
	private $routeName = '';

	private $user;

	public static function createFromGlobals(): Request
	{
		if (isset(static::$instance))
		{
			return static::$instance;
		}
		static::$instance = new static();
		$request = static::$instance;
		$request->queries = $_GET;
		$request->post = $_POST;
		$request->cookies = $_COOKIE;
		$request->files = $_FILES;
		$request->method = $_SERVER['REQUEST_METHOD'];
		$request->requestUrl = $_SERVER['REQUEST_URI'];
		$headersLines = getallheaders(); //apache only
		foreach ($headersLines as $header => $line)
		{
			$values = array_map('trim', preg_split("/[;,]/", $line));
			$request->headers[strtolower($header)] = new Header($header, $values);
		}
		$request->headers['user-agent'] = new Header('User-Agent', $headersLines['User-Agent']);

		return $request;
	}

	public function getMethod(): string
	{
		return $this->method;
	}

	public function getRequestUrl(): string
	{
		return $this->requestUrl;
	}

	/**
	 * @throws Error\NoSuchQueryParameterException
	 */
	public function getHeaderByName(string $key): Header
	{
		$keyLowerCase = strtolower($key);

		return $this->getRequestParameters($keyLowerCase, $this->headers, 'Not found such header: ' . $key);
	}

	/**
	 * @throws Error\NoSuchQueryParameterException
	 */
	public function getQueriesByName(string $key)
	{
		return $this->getRequestParameters(
			$key,
			$this->queries,
			'Not found parameter with such key in query string: ' . $key
		);
	}

	/**
	 * @throws Error\NoSuchQueryParameterException
	 */
	public function getQueriesOrDefaultList(array $keys): array
		{
			$finalQuery = [];
			foreach ($keys as $key => $default)
			{
				if ($this->containsQuery($key))
				{
					$finalQuery[$key] = $this->getQueriesByName($key);
				}
				else
				{
					$finalQuery[$key] = $default;
				}
			}
			return $finalQuery;
		}



	/**
	 * @throws Error\NoSuchQueryParameterException
	 */
	public function getPostParametersByName(string $key)
	{
		return $this->getRequestParameters(
			$key,
			$this->post,
			'Not found parameter with such key in posts body: ' . $key
		);
	}

	/**
	 * @throws Error\NoSuchQueryParameterException
	 */
	public function getCookiesParametersByName(string $key)
	{
		return $this->getRequestParameters(
			$key,
			$this->cookies,
			'Not found parameter with such key in cookies: ' . $key
		);
	}

	/**
	 * @throws Error\NoSuchQueryParameterException
	 */
	public function getFilesByName(string $key)
	{
		return $this->getRequestParameters($key, $this->files, 'Not found such files: ' . $key);
	}

	/**
	 * @throws Error\NoSuchQueryParameterException
	 */
	public function getSessionParametersByName(string $key)
	{
		return $this->getRequestParameters($key, $_SESSION, 'Not found such parameters in session: ' . $key);
	}

	public function containsQuery(string $key): bool
	{
		return array_key_exists($key, $this->queries);
	}

	public function containsPost(string $key): bool
	{
		return array_key_exists($key, $this->post);
	}

	public function containsCookie(string $key): bool
	{
		return array_key_exists($key, $this->cookies);
	}

	public function containsFile(string $key): bool
	{
		return array_key_exists($key, $this->files);
	}

	public function containsSession(string $key): bool
	{
		return array_key_exists($key, $_SESSION);
	}

	/**
	 * @throws Error\NoSuchQueryParameterException
	 */
	private function getRequestParameters(string $key, array $from, string $exceptionMessage)
	{
		if (!array_key_exists($key, $from))
		{
			throw new Error\NoSuchQueryParameterException($exceptionMessage);
		}

		return $from[$key];
	}

	/**
	 * @return array
	 */
	public function getQueries(): array
	{
		return $this->queries;
	}

	/**
	 * @return array
	 */
	public function getPost(): array
	{
		return $this->post;
	}

	/**
	 * @return array
	 */
	public function getCookies(): array
	{
		return $this->cookies;
	}

	/**
	 * @return array
	 */
	public function getSession(): array
	{
		return $_SESSION;
	}

	/**
	 * @return User
	 */
	public function getUser(): User
	{
		return $this->user;
	}

	/**
	 * @param User $user
	 */
	public function setUser(User $user): void
	{
		$this->user = $user;
	}

	/**
	 * @return string
	 */
	public function getRouteName(): string
	{
		return $this->routeName;
	}

	/**
	 * @param string $routeName
	 */
	public function setRouteName(string $routeName): void
	{
		$this->routeName = $routeName;
	}

	/**
	 * @return bool
	 */
	public function isAdmin(): bool
	{
		return $this->getUser()->getRole()->getName() == UserEnum::Admin();
	}

	/**
	 * @return bool
	 */
	public function isAuthenticated(): bool
	{
		return $this->getUser()->getRole()->getName() != UserEnum::Guest();
	}

}
