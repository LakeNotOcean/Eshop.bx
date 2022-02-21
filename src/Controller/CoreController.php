<?php

namespace Up\Controller;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\User\UserEnum;

class CoreController
{
	private $templateProcessor;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 */
	public function __construct(TemplateProcessorInterface $templateProcessor)
	{
		$this->templateProcessor = $templateProcessor;
	}

	public function get404(Request $request)
	{
		return (new Response())->withBodyHTML(
			$this->templateProcessor->render('404.php', [], 'layout/main.php', [
				'isAuthenticated' => $request->isAuthenticated(),
				'isAdmin' => $request->isAdmin(),
				'userName' => $request->getUser()->getName()
			])
		);
	}
}
