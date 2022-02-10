<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Response;
use Up\Core\Message\Request;
use Up\Core\TemplateProcessor;
use Up\Service\CatalogService\CatalogServiceInterface;


class OrderController
{
	protected $templateProcessor;
	protected $catalogService;

	public function __construct(TemplateProcessor $templateProcessor, CatalogServiceInterface $catalogService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->catalogService = $catalogService;
	}

	public function makeOrder(Request $request, int $id): Response
	{
		$item = $this->catalogService->getItemById($id);
		$items = [$item,$item,$item,$item];
		$page = $this->templateProcessor->render('make-order.php', [
			'items' => $items
		], 'order.php', [
			'cost' => $this->calculateTotalCost($items),
			'orderSize' => count($items)
		]);
		$response = new Response();
		return $response->withBodyHTML($page);
	}

	private function calculateTotalCost(array $items): int
	{
		$cost = 0;
		foreach ($items as $item)
		{
			$cost += $item->getPrice();
		}
		return $cost;
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function finishOrder(Request $request): Response
	{
		//TODO(catalogService->getItemsByIds) for id array
		$itemIds = $request->getPostParametersByName('itemIds');
		$items = [];
		foreach ($itemIds as $id)
		{
			$items[] = $this->catalogService->getItemById($id);
		}
		$page = $this->templateProcessor->render('finish-order.php', [
			'items' => $items
		], 'order.php', [
			'cost' => $this->calculateTotalCost($items),
			'orderSize' => count($items)
		]);
		$response = new Response();
		return $response->withBodyHTML($page);
	}

}
