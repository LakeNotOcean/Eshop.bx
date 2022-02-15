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
						'request' => $request,
						'exception' => $throwable,
					],                               'layout/main.php', [])
				);
			}
		}

		return call_user_func($this->getResponse, $request, ...$params);
	}
}
