<?php

namespace Up\DAO\OrderDAO;

use Up\Entity\Order;


interface OrderDAOInterface
{
	public function getOrders(): array;

	public function getOrderIdByOrder(Order $order): int;

	public function addOrder(Order $order): void;

	public function addOrderItems(int $orderId, array $itemIds): void;
}
