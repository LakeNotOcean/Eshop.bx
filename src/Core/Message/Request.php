<?php

namespace Up\Core\Message;

class Request
{
	private $queries = [];
	private $post = [];
	private $cookies = [];
	private $files = [];
	private $session = [];
	private $method = '';
	private $headers = [];
	private $requestUrl = '';

	/**
	 * @return Request
	 */
	public static function createFromGlobals(): Request
	{
		$request = new Request();
		$request->queries = $_GET;
		$request->post = $_POST;
		$request->cookies = $_COOKIE;
		$request->files = $_FILES;
		$request->session = $_SESSION;
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
		return $this->getRequestParameters($key, $this->cookies, 'Not found such files: ' . $key);
	}

	/**
	 * @throws Error\NoSuchQueryParameterException
	 */
	public function getSessionParametersByName(string $key)
	{
		return $this->getRequestParameters($key, $this->session, 'Not found such parameters in session: ' . $key);
	}

	public function isQueryContains(string $key): bool
	{
		return in_array($key, $this->queries);
	}

	public function isPostContains(string $key): bool
	{
		return in_array($key, $this->post);
	}

	public function isCookiesContains(string $key): bool
	{
		return in_array($key, $this->cookies);
	}

	public function isFilesContains(string $key): bool
	{
		return in_array($key, $this->files);
	}

	public function isSessionContains(string $key): bool
	{
		return in_array($key, $this->session);
	}

	/**
	 * @throws Error\NoSuchQueryParameterException
	 */
	private function getRequestParameters(string $key, array $from, string $exceptionMessage)
	{
		if (!in_array($key, $from))
		{
			throw new Error\NoSuchQueryParameterException($exceptionMessage);
		}

		return $from[$key];
	}
}