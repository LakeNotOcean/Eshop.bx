<?php

namespace Up\Service\OrderService;

use Up\DAO\ItemDAO\ItemDAOInterface;
use Up\DAO\OrderDAO\OrderDAOInterface;
use Up\Entity\Order\Order;
use Up\Entity\Order\OrderStatus;
use Up\Service\ItemService\ItemServiceInterface;

class OrderService implements OrderServiceInterface
{
	protected $orderDAO;
	protected $itemDao;
	protected $itemService;

	/**
	 * @param \Up\DAO\OrderDAO\OrderDAOmysql $orderDAO
	 * @param \Up\DAO\ItemDAO\ItemDAOmysql $orderDAO
	 * @param \Up\Service\ItemService\ItemService $itemService
	 */
	public function __construct(OrderDAOInterface $orderDAO, ItemDAOInterface $itemDao, ItemServiceInterface $itemService)
	{
		$this->orderDAO = $orderDAO;
		$this->itemDao = $itemDao;
		$this->itemService = $itemService;
	}

	public function getOrders(array $limitOffset, OrderStatus $status, string $searchQuery): array
	{
		$orders = $this->orderDAO->getOrders($limitOffset['offset'], $limitOffset['amountItems'], $status, $searchQuery);
		foreach ($orders as $order)
		{
			$items = $this->itemDao->getItemsByOrderId($order->getId());
			$order->setItems($items);
		}
		return $orders;
	}

	public function getOrdersAmount(OrderStatus $status, string $searchQuery): int
	{
		return $this->orderDAO->getItemsAmount($status, $searchQuery);
	}

	public function getOrdersByUserId(array $limitOffset, int $userId): array
	{
		return $this->orderDAO->getOrdersByUserId($limitOffset['offset'], $limitOffset['amountItems'], $userId);
	}

	public function getAmountOrdersByUserId(int $userId): int
	{
		return $this->orderDAO->getAmountOrdersByUserId($userId);
	}

	public function saveOrder(Order $order): void
	{
		$this->orderDAO->addOrder($order);
		$orderId = $this->orderDAO->getLastInsertId();
		$this->orderDAO->addOrderItems($orderId, $order->getItems());
	}

	public function updateOrderStatus(int $orderId, string $orderNewStatus): void
	{
		$this->orderDAO->updateOrderStatus($orderId, $orderNewStatus);
	}

	public function deleteOrder(int $orderId): void
	{
		$this->orderDAO->deleteOrder($orderId);
	}

	public function checkThatUserBoughtItem(int $userId, int $itemId): bool
	{
		return $this->orderDAO->existUsersFinishedOrderByItemId($userId, $itemId);
	}
}
