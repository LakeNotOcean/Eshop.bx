<?php

namespace Up\DAO\OrderDAO;

use Up\Entity\Order\Order;
use Up\Entity\Order\OrderStatus;


interface OrderDAOInterface
{
	public function getOrders(int $offset, int $amountItems, OrderStatus $status, string $searchQuery): array;

	public function getItemsAmount(OrderStatus $status, string $searchQuery): int;

	public function addOrder(Order $order): void;

	public function addOrderItems(int $orderId, array $items): void;
}
