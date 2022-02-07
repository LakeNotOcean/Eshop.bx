<?php

namespace Up\Controller;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessor;
use Up\Service\CatalogService;

class CatalogController
{
	protected $templateProcessor;
	protected $catalogService;

	public function __construct(TemplateProcessor $templateProcessor, CatalogService $catalogService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->catalogService = $catalogService;
	}

	public function getItems(Request $request): Response
	{
		$items = $this->catalogService->getItems();
		$pages = $this->templateProcessor->render('catalog.php', ['items' => $items], 'main.php', []);
		$response = new Response();
		$response = $response->withBodyHTML($pages);

		return $response;
	}

	public function getResultCount(Request $request): int
	{
		return $this->catalogService->getResultCount();
	}

}