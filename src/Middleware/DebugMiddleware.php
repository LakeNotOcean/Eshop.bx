<?php

namespace Up\Middleware;

use Throwable;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;
use Up\Core\Settings\Settings;
use Up\Core\TemplateProcessorInterface;
use Up\LayoutManager\MainLayoutManager;

class DebugMiddleware extends AbstractMiddleware
{

	private $mainLayoutManager;

	/**
	 * @param \Up\LayoutManager\MainLayoutManager $mainLayoutManager
	 */
	public function __construct(MainLayoutManager $mainLayoutManager)
	{
		$this->mainLayoutManager = $mainLayoutManager;
	}

	public function __invoke(Request $request, ...$params): Response
	{
		if (Settings::getInstance()->getSettings('isDev'))
		{
			try
			{
				return call_user_func($this->getResponse, $request, ...$params);
			}
			catch (Throwable $throwable)
			{
				return (new Response())->withStatus(418)->withBodyHTML(
					$this->mainLayoutManager->render('debug.php', [
						'request' => $this->getRequestArray($request),
						'exceptions' => $this->getExceptionTrace($throwable),
					])
				);
			}
		}

		return call_user_func($this->getResponse, $request, ...$params);
	}

	private function getExceptionTrace(Throwable $throwable): array
	{
		$exceptions = [$this->getThrowableInfo($throwable)];
		foreach ($throwable->getTrace() as $exception)
		{
			$exceptionData = $this->getExceptionInfo($exception);
			if (isset($exceptionData))
			{
				$exceptions[] = $exceptionData;
			}
		}
		return $exceptions;
	}

	private function getThrowableInfo(Throwable $throwable): array
	{
		$file = $throwable->getFile();
		$line = $throwable->getLine();
		$function = $throwable->getTrace()[0]['function'];
		$message = $throwable->getMessage();
		return $this->getExceptionData($file, $line, $function, $message);
	}

	private function getExceptionInfo(array $exception): ?array
	{
		$file = $exception['file'] ?? null;
		$line = $exception['line'] ?? null;
		if (!isset($file, $line))
		{
			return null;
		}
		$function = $exception['function'] ?? null;
		$message = $exception['message'] ?? '';
		return $this->getExceptionData($file, $line, $function, $message);
	}

	private function getExceptionData(string $file, string $line, string $function, string $message): array
	{
		$exceptionData = [];
		$exceptionData['info'] = "$file, line $line, in $function";
		if (!empty($message))
		{
			$exceptionData['info'] .= ", with message: $message";
		}
		$exceptionData['codeLine'] = $this->getCodeLine($file, $line);
		return $exceptionData;
	}

	private function getCodeLine(string $file, string $line): string
	{
		$lines = file($file);
		return "$line. {$lines[$line-1]}";
	}

	private function getRequestArray(Request $request): array
	{
		$requestArray = [];

		$queries = $request->getQueries();
		if (isset($queries))
		{
			$requestArray['Queries'] = $queries;
		}

		$post = $request->getPost();
		if (isset($post))
		{
			$requestArray['Post'] = $post;
		}

		$cookies = $request->getCookies();
		if (isset($cookies))
		{
			$requestArray['Cookies'] = $cookies;
		}

		$session = $request->getSession();
		if (isset($session))
		{
			$requestArray['Session'] = $session;
		}

		return $requestArray;
	}

}
