<?php

namespace Up\Service\OrderService;

use Up\Entity\Order\Order;
use Up\Entity\Order\OrderStatus;


interface OrderServiceInterface
{

	public function getOrders(array $limitOffset, OrderStatus $status, string $searchQuery): array;

	public function getOrdersAmount(OrderStatus $status, string $searchQuery): int;

	public function saveOrder(Order $order): void;

	public function updateOrderStatus(int $orderId, string $orderNewStatus): void;

	public function deleteOrder(int $orderId): void;

	public function checkThatUserBoughtItem(int $userId, int $itemId): bool;

}
