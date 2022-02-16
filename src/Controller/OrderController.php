<?php

namespace Up\Controller;

use DateTime;
use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\Order;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\OrderService\OrderServiceInterface;


class OrderController
{
	protected $templateProcessor;
	protected $itemService;
	protected $orderService;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\Service\ItemService\ItemService $itemService
	 * @param \Up\Service\OrderService\OrderService $orderService
	 */
	public function __construct(TemplateProcessorInterface $templateProcessor,
								ItemServiceInterface $itemService,
								OrderServiceInterface $orderService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->itemService = $itemService;
		$this->orderService = $orderService;
	}

	public function makeOrder(Request $request, int $id): Response
	{
		$item = $this->itemService->getItemById($id);
		$items = [$item];
		$page = $this->templateProcessor->render('make-order.php', [
			'items' => $items,
		],                                       'layout/order.php', [
													 'cost' => $this->calculateTotalCost($items),
													 'orderSize' => count($items),
												 ]);

		return (new Response())->withBodyHTML($page);
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

		$first_name = $request->getPostParametersByName('first-name');
		$second_name = $request->getPostParametersByName('second-name');

		$customerName = $first_name . ' ' . $second_name;
		$phone = $request->getPostParametersByName('phone');
		$email = $request->getPostParametersByName('email');
		$comment = $request->getPostParametersByName('comment');

		$order = new Order($customerName, $phone, $email, $comment);
		$order->setStatus('IN_PROCESSING'); //TODO enum and OrderStatus::IN_PROCESSING()
		$now = $this->getDatetime();
		$order->setDateCreate($now);
		$order->setDateUpdate($now);
		$order->setItems($items);
		//TODO get user from session and $order->setUser($user);

		$this->orderService->saveOrder($order);

		$page = $this->templateProcessor->render('finish-order.php', [
			'items' => $items,
		],                                       'layout/order.php', [
													 'cost' => $this->calculateTotalCost($items),
													 'orderSize' => count($items),
												 ]);

		return (new Response())->withBodyHTML($page);
	}

	public function getOrders(Request $request): Response
	{
		$orders = $this->orderService->getOrders();
		$page = $this->templateProcessor->render('orders.php', [
			'orders' => $orders,
		],                                       'layout/admin-main.php', []);

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

	private function getDatetime(): string
	{
		return date('Y-m-d H:i:s');
	}

}
