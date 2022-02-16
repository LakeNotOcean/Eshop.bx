<?php

namespace Up\Service\OrderService;

use Up\DAO\ItemDAO\ItemDAOInterface;
use Up\DAO\OrderDAO\OrderDAOInterface;
use Up\Entity\Order\Order;


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

	public function getOrders(): array
	{
		$orders = $this->orderDAO->getOrders();
		foreach ($orders as $order)
		{
			$items = $this->itemDao->getItemsByOrderId($order->getId());
			$order->setItems($items);
		}
		return $orders;
	}

	public function saveOrder(Order $order): void
	{
		$this->orderDAO->addOrder($order);
		$orderId = $this->orderDAO->getLastInsertId();
		$this->orderDAO->addOrderItems($orderId, $order->getItems());
	}

}
