<?php

namespace Up\Service\OrderService;

use Up\DAO\OrderDAO\OrderDAOInterface;
use Up\Entity\Order;


class OrderService implements OrderServiceInterface
{
	protected $orderDAO;

	/**
	 * @param \Up\DAO\OrderDAO\OrderDAOmysql $orderDAO
	 */
	public function __construct(OrderDAOInterface $orderDAO)
	{
		$this->orderDAO = $orderDAO;
	}

	public function getOrders(): array
	{
		// TODO: Implement getOrders() method.
		return [];
	}

	public function saveOrder(Order $order): void
	{
		$this->orderDAO->addOrder($order);
		$orderId = $this->orderDAO->getOrderIdByOrder($order);
		$this->orderDAO->addOrderItems($orderId, $order->getItemIds());
	}

}
