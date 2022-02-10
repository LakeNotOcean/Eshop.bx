<?php

namespace Up\Controller;

use Error;
use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Migration\MigrationManager;
use Up\Core\TemplateProcessor;
use Up\Service\ImageService\ImageServiceInterface;
use Up\Service\ItemService\ItemServiceInterface;

class ItemController
{
	protected $templateProcessor;
	protected $itemService;
	protected $imageService;

	public function __construct(
		TemplateProcessor     $templateProcessor,
		ItemServiceInterface  $itemService,
		ImageServiceInterface $imageService
	)
	{
		$this->templateProcessor = $templateProcessor;
		$this->itemService = $itemService;
		$this->imageService = $imageService;
	}

	public function getItems(Request $request): Response
	{
		$items = $this->itemService->getItems();
		$pages = $this->templateProcessor->render('catalog.php', ['items' => $items], 'main.php', []);
		$response = new Response();

		return $response->withBodyHTML($pages);
	}

	public function getItem(Request $request, $id): Response
	{
		$item = $this->itemService->getItemById($id);
		$pages = $this->templateProcessor->render('item.php', ['item' => $item], 'main.php', []);
		$response = new Response();

		return $response->withBodyHTML($pages);
	}
}
