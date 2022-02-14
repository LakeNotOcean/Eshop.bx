<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Service\ItemService\ItemServiceInterface;


class OrderController
{
	protected $templateProcessor;
	protected $itemService;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\Service\ItemService\ItemService $itemService
	 */
	public function __construct(TemplateProcessorInterface $templateProcessor, ItemServiceInterface $itemService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->itemService = $itemService;
	}

	public function makeOrder(Request $request, int $id): Response
	{
		$item = $this->itemService->getItemById($id);
		$items = [$item];
		$page = $this->templateProcessor->render('make-order.php', [
			'items' => $items,
		],                                       'order.php', [
													 'cost' => $this->calculateTotalCost($items),
													 'orderSize' => count($items),
												 ]);

		return (new Response())->withBodyHTML($page);
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
			$items[] = $this->itemService->getItemById($id);
		}
		$page = $this->templateProcessor->render('finish-order.php', [
			'items' => $items,
		],                                       'order.php', [
													 'cost' => $this->calculateTotalCost($items),
													 'orderSize' => count($items),
												 ]);

		return (new Response())->withBodyHTML($page);
	}

}
