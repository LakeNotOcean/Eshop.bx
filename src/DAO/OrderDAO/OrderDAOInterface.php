<?php

namespace Up\DAO\OrderDAO;

use Up\Entity\Order\Order;
use Up\Entity\Order\OrderStatus;


interface OrderDAOInterface
{

	public function getOrders(int $offset, int $amountItems, OrderStatus $status, string $searchQuery): array;

	public function getOrdersByUserId(int $offset, int $amountItems, int $userId): array;

	public function getAmountOrdersByUserId(int $userId): int;

	public function getItemsAmount(OrderStatus $status, string $searchQuery): int;

	public function addOrder(Order $order): int;

	public function addOrderItems(int $orderId, array $items): void;

	public function updateOrderStatus(int $orderId, string $orderNewStatus): void;

	public function deleteOrder(int $orderId): void;

	public function existUsersFinishedOrderByItemId(int $userId, int $itemId): bool;

}
