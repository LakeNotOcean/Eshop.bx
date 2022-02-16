<?php

namespace Up\Middleware;

use Throwable;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;
use Up\Core\Settings\Settings;
use Up\Core\TemplateProcessorInterface;


class DebugMiddleware extends AbstractMiddleware
{

	private $templateProcessor;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 */
	public function __construct(TemplateProcessorInterface $templateProcessor)
	{
		$this->templateProcessor = $templateProcessor;
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
				return (new Response())->withBodyHTML(
					$this->templateProcessor->render('debug.php', [
						'request' => $this->getRequestArray($request),
						'exceptions' => $this->getExceptionTrace($throwable),
					],                               'layout/main.php', [])
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
		return $this->getExceptionData($file, $line, $function);
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
		return $this->getExceptionData($file, $line, $function);
	}

	private function getExceptionData(string $file, string $line, string $function): array
	{
		$exceptionData = [];
		$exceptionData['info'] = "$file, line $line, in $function";
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
