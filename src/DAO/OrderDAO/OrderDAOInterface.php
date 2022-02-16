<?php

namespace Up\DAO\OrderDAO;

use Up\Entity\Order\Order;


interface OrderDAOInterface
{
	public function getOrders(): array;

	public function getLastInsertId(): int;

	public function addOrder(Order $order): void;

	public function addOrderItems(int $orderId, array $items): void;
}
