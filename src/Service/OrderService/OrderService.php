<?php

namespace Up\Service\OrderService;

use Up\DAO\ItemDAO\ItemDAOInterface;
use Up\DAO\OrderDAO\OrderDAOInterface;
use Up\Entity\Order\Order;
use Up\Entity\Order\OrderStatus;


class OrderService implements OrderServiceInterface
{
	protected $orderDAO;
	protected $itemDao;

	/**
	 * @param \Up\DAO\OrderDAO\OrderDAOmysql $orderDAO
	 * @param \Up\DAO\ItemDAO\ItemDAOmysql $orderDAO
	 */
	public function __construct(OrderDAOInterface $orderDAO, ItemDAOInterface $itemDao)
	{
		$this->orderDAO = $orderDAO;
		$this->itemDao = $itemDao;
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

	public function saveOrder(Order $order): void
	{
		$this->orderDAO->addOrder($order);
		$orderId = $this->orderDAO->getLastInsertId();
		$this->orderDAO->addOrderItems($orderId, $order->getItems());
	}

}
