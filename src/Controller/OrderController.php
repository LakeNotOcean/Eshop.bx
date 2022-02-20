<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\Order\Order;
use Up\Entity\Order\OrderStatus;
use Up\Entity\User\UserEnum;
use Up\Lib\Paginator\Paginator;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\OrderService\OrderServiceInterface;


class OrderController
{
	protected $templateProcessor;
	protected $itemService;
	protected $orderService;

	protected $ordersOnPage = 10;

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
			'user' => $request->getUser()
		],'layout/order.php', [
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
		$order->setStatus(OrderStatus::IN_PROCESSING());
		$now = $this->getDatetime();
		$order->setDateCreate($now);
		$order->setDateUpdate($now);
		$order->setItems($items);
		$order->setUser($request->getUser());

		$this->orderService->saveOrder($order);

		$page = $this->templateProcessor->render('finish-order.php', [
			'items' => $items,
		], 'layout/order.php', [
			'cost' => $order->getTotalCost(),
			'orderSize' => count($items),
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function getOrders(Request $request): Response
	{
		$isAuthenticated = $request->getUser()->getRole()->getName() != UserEnum::Guest();
		$isAdmin = $request->getUser()->getRole()->getName() == UserEnum::Admin();

		$currentPage = $request->containsQuery('page') ? (int)$request->getQueriesByName('page') : 1;
		$status = $request->containsQuery('status') ? OrderStatus::from($request->getQueriesByName('status'))
			: OrderStatus::IN_PROCESSING();
		$query = $request->containsQuery('query') ? $request->getQueriesByName('query') : '';

		$orders = $this->orderService->getOrders(
			Paginator::getLimitOffset($currentPage, $this->ordersOnPage), $status, $query);
		$ordersAmount = $this->orderService->getOrdersAmount($status, $query);
		$pagesAmount = Paginator::getPageCount($ordersAmount, $this->ordersOnPage);

		$page = $this->templateProcessor->render('orders.php', [
			'orders' => $orders,
			'currentPage' => $currentPage,
			'itemsAmount' => $ordersAmount,
			'pagesAmount' => $pagesAmount,
			'query' => $query,
		], 'layout/main.php', [
			'isAuthenticated' => $isAuthenticated,
			'isAdmin' => $isAdmin
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function changeOrderStatus(Request $request): Response
	{
		$orderId = $request->getPostParametersByName('order-id');
		$orderNewStatus = $request->getPostParametersByName('order-status');

		$this->orderService->updateOrderStatus($orderId, $orderNewStatus);

		return (new Response())->withBodyHTML('');
	}

	public function deleteOrder(Request $request): Response
	{
		$orderId = $request->getPostParametersByName('order-id');

		$this->orderService->deleteOrder($orderId);

		return (new Response())->withBodyHTML('');
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
