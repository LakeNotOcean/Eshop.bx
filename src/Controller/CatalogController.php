<?php

namespace Up\Controller;

use Up\Core\DataBase\BaseDatabase;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessor;
use Up\Core\TemplateProcessorImpl;
use Up\Service\CatalogService;
use Up\Service\CatalogServiceImpl;


class CatalogController
{
	protected $templateProcessor;
	protected $catalogService;

	public function __construct(TemplateProcessor $templateProcessor, CatalogService $catalogService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->catalogService = $catalogService;
	}

	public function getItems(): Response
	{
		$items = $this->catalogService->getItems();
		$pages = $this->templateProcessor->render('catalog.php', ['items'=>$items], 'main.php', []);
		$response = new Response();
		$response = $response->withBodyHTML($pages);
		return $response;
	}

	public function getResultCount(): int
	{
		return $this->catalogService->getResultCount();
	}

}