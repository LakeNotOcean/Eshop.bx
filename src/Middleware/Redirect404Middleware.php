<?php

namespace Up\Middleware;

use Throwable;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Middleware\AbstractMiddleware;
use Up\Core\TemplateProcessor;

class Redirect404Middleware extends AbstractMiddleware
{
	private $templateProcessor;

	/**
	 * @param \Up\Core\TemplateProcessorImpl $templateProcessor
	 */
	public function __construct(TemplateProcessor $templateProcessor)
	{
		$this->templateProcessor = $templateProcessor;
	}

	public function __invoke(Request $request, ...$params): Response
	{
		try
		{
			return call_user_func($this->getResponse, $request, ...$params);
		}
		catch (Throwable $throwable)
		{
			return (new Response())->withBodyHTML(
				$this->templateProcessor->render('404.php', [], 'main.php', [])
			);
		}
	}
}