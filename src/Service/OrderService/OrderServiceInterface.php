<?php

namespace Up\Service\OrderService;

use Up\Entity\Order;


interface OrderServiceInterface
{
	public function getOrders(): array;

	public function saveOrder(Order $order): void;
}
