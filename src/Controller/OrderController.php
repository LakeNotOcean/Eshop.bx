<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\Item;
use Up\Entity\Order\Order;
use Up\Entity\Order\OrderStatus;
use Up\LayoutManager\MainLayoutManager;
use Up\LayoutManager\OrderLayoutManager;
use Up\Lib\Paginator\Paginator;
use Up\Service\CartService\CartServiceInterface;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\OrderService\OrderServiceInterface;

class OrderController
{
	protected $templateProcessor;
	protected $mainLayoutManager;
	protected $orderLayoutManager;
	protected $itemService;
	protected $cartService;
	protected $orderService;

	protected $ordersOnPage = 10;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\LayoutManager\MainLayoutManager $mainLayoutManager
	 * @param \Up\LayoutManager\OrderLayoutManager $orderLayoutManager
	 * @param \Up\Service\CartService\CartService $cartService
	 * @param \Up\Service\ItemService\ItemService $itemService
	 * @param \Up\Service\OrderService\OrderService $orderService
	 */
	public function __construct(
		TemplateProcessorInterface	$templateProcessor,
		MainLayoutManager			$mainLayoutManager,
		OrderLayoutManager			$orderLayoutManager,
		CartServiceInterface		$cartService,
		ItemServiceInterface		$itemService,
		OrderServiceInterface		$orderService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->cartService = $cartService;
		$this->mainLayoutManager = $mainLayoutManager;
		$this->orderLayoutManager = $orderLayoutManager;
		$this->itemService = $itemService;
		$this->orderService = $orderService;
	}

	public function makeOrder(Request $request): Response
	{
		$items = $this->cartService->getItemsFromCart();
		$page = $this->orderLayoutManager
			->setOrderItems($items)
			->render('make-order.php', [
			'items' => $items,
			'user' => $request->getUser()
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function finishOrder(Request $request): Response
	{
		if (!(
			$request->containsPost('items') &&
			$request->containsPost('first-name') &&
			$request->containsPost('second-name') &&
			$request->containsPost('phone') &&
			$request->containsPost('email') &&
			$request->containsPost('comment')
		))
		{
			return $this->getUnsuccessedResponse();
		}

		try
		{
			$itemsData = json_decode($request->getPostParametersByName('items'), true, 512, JSON_THROW_ON_ERROR);
		}
		catch (\JsonException $e)
		{
			return $this->getUnsuccessedResponse();
		}

		$items = [];

		foreach ($itemsData as $itemsDatum)
		{
			for ($i = 0; $i < $itemsDatum['count']; $i++)
			{
				$items[] = (new Item())->setId($itemsDatum['id']);
			}
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
		$this->cartService->clearCart();

		$page = $this->orderLayoutManager
			->setOrderItems($items)
			->render('finish-order.php', [
			'items' => $items,
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function getOrders(Request $request): Response
	{
		$currentPage = $request->containsQuery('page') ? (int)$request->getQueriesByName('page') : 1;
		$status = $request->containsQuery('status') ? OrderStatus::from($request->getQueriesByName('status'))
			: OrderStatus::IN_PROCESSING();
		$query = $request->containsQuery('query') ? $request->getQueriesByName('query') : '';

		$orders = $this->orderService->getOrders(
			Paginator::getLimitOffset($currentPage, $this->ordersOnPage), $status, $query);
		$ordersAmount = $this->orderService->getOrdersAmount($status, $query);
		$pagesAmount = Paginator::getPageCount($ordersAmount, $this->ordersOnPage);

		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);

		$page = $this->mainLayoutManager->render('orders.php', [
			'orders' => $orders,
			'paginator' => $paginator,
			'query' => $query,
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

	private function getDatetime(): string
	{
		return date('Y-m-d H:i:s');
	}

	private function getUnsuccessedResponse()
	{
		return (new Response())->withStatus(409)->withBodyJSON(
			[
				'success' => false
			]
		);
	}
}
