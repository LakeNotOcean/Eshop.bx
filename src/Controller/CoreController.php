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
		$isAuthenticated = $request->getUser()->getRole()->getName() != UserEnum::Guest();
		$isAdmin = $request->getUser()->getRole()->getName() == UserEnum::Admin();

		return (new Response())->withBodyHTML(
			$this->templateProcessor->render('404.php', [], 'layout/main.php', [
				'isAuthenticated' => $isAuthenticated,
				'isAdmin' => $isAdmin
			])
		);
	}
}