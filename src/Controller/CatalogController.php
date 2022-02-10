<?php

namespace Up\Controller;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessor;
use Up\Service\CatalogService\CatalogServiceInterface;


class CatalogController
{
	protected $templateProcessor;
	protected $catalogService;

	public function __construct(TemplateProcessor $templateProcessor, CatalogServiceInterface $catalogService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->catalogService = $catalogService;
	}

	public function getItems(Request $request): Response
	{
		$items = $this->catalogService->getItems();
		$pages = $this->templateProcessor->render('catalog.php', ['items' => $items], 'main.php', []);
		$response = new Response();

		return $response->withBodyHTML($pages);
	}

	public function getResultCount(Request $request): int
	{
		return $this->catalogService->getResultCount();
	}

	public function getItem(Request $request, int $id):Response
	{
		$item = $this->catalogService->getItemById($id);
		$pages = $this->templateProcessor->render('item.php', ['item' => $item], 'main.php', []);
		$response = new Response();
		return $response->withBodyHTML($pages);
	}

}
