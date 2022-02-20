<?php

namespace Up\DAO\OrderDAO;

use Up\Core\Database\DefaultDatabase;
use Up\DAO\AbstractDAO;
use Up\Entity\Order\Order;
use Up\Entity\Order\OrderStatus;

class OrderDAOmysql extends AbstractDAO implements OrderDAOInterface
{

	/**
	 * @param \Up\Core\Database\DefaultDatabase $dbConnection
	 */
	public function __construct(DefaultDatabase $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	/**
	 * @return array<Order>
	 */
	public function getOrders(int $offset, int $amountItems, OrderStatus $status, string $searchQuery): array
	{
		$preparedStatement = $this->dbConnection->prepare($this->getOrdersQuery($offset, $amountItems));
		$preparedStatement->execute([$status->getValue(), "%$searchQuery%"]);

		$orders = [];
		while ($row = $preparedStatement->fetch())
		{
			$order = new Order($row['CUSTOMER_NAME'], $row['PHONE'], $row['EMAIL'], $row['COMMENT']);
			$order->setId($row['ID']);
			$order->setStatus(OrderStatus::from($row['STATUS']));
			$order->setDateCreate($row['DATE_CREATE']);
			$order->setDateUpdate($row['DATE_UPDATE']);
			$orders[] = $order;
		}
		return $orders;
	}

	private function getOrdersQuery(int $offset, int $amountItems): string
	{
		return "SELECT * FROM up_order 
				WHERE STATUS = ? and CUSTOMER_NAME like ? 
				ORDER BY DATE_UPDATE
				LIMIT {$offset}, {$amountItems};";
	}

	public function getItemsAmount(OrderStatus $status, string $searchQuery): int
	{
		$preparedStatement = $this->dbConnection->prepare($this->getItemsAmountQuery());
		$preparedStatement->execute([$status, "%$searchQuery%"]);

		return $preparedStatement->fetch()['orders_count'];
	}

	private function getItemsAmountQuery(): string
	{
		return "
			SELECT count(ID) AS orders_count FROM up_order 
			WHERE STATUS = ? and CUSTOMER_NAME like ?";
	}

	public function addOrder(Order $order): void
	{
		$preparedStatement = $this->getInsertPrepareStatement(
			'up_order',
			['CUSTOMER_NAME', 'PHONE', 'EMAIL', 'COMMENT', 'STATUS', 'DATE_CREATE', 'DATE_UPDATE', 'USER_ID']
		);
		$preparedStatement->execute($this->prepareOrder($order));
	}

	private function prepareOrder(Order $order): array
	{
		return [$order->getCustomerName(), $order->getPhone(), $order->getEmail(), $order->getComment(),
				$order->getStatus(), $order->getDateCreate(), $order->getDateUpdate(), $order->getUser()->getId()];
	}

	public function addOrderItems(int $orderId, array $items): void
	{
		$preparedStatement = $this->getInsertPrepareStatement(
			'`up_order-item`',
			['ORDER_ID', 'ITEM_ID', 'COUNT']
		);
		$data = $this->prepareOrderItems($orderId, $items);
		foreach ($data as $row)
		{
			$preparedStatement->execute($row);
		}
	}

	public function updateOrderStatus(int $orderId, string $orderNewStatus): void
	{
		$query = "UPDATE up_order SET STATUS = ? WHERE ID = $orderId;";
		$preparedStatement = $this->dbConnection->prepare($query);
		$preparedStatement->execute([$orderNewStatus]);
	}

	private function prepareOrderItems(int $orderId, array $items): array
	{
		$itemsCount = [];
		foreach ($items as $item)
		{
			if (isset($itemsCount[$item->getId()]))
			{
				$itemsCount[$item->getId()]++;
			}
			else
			{
				$itemsCount[$item->getId()] = 1;
			}
		}

		$result = [];
		foreach ($itemsCount as $itemId => $itemCount)
		{
			$result[] = [$orderId, $itemId, $itemCount];
		}

		return $result;
	}

}
