<?php

namespace Up\DAO\OrderDAO;

use Up\Core\Database\DefaultDatabase;
use Up\DAO\AbstractDAO;
use Up\Entity\Order;


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
	public function getOrders(): array
	{
		$preparedStatement = $this->getSelectPrepareStatement('up_order');
		$preparedStatement->execute();

		$orders = [];
		while ($row = $preparedStatement->fetch())
		{
			$order = new Order($row['CUSTOMER_NAME'], $row['PHONE'], $row['EMAIL'], $row['COMMENT']);
			$order->setId($row['ID']);
			$order->setStatus($row['STATUS']);
			$order->setDateCreate($row['DATE_CREATE']);
			$order->setDateUpdate($row['DATE_UPDATE']);
			$orders[] = $order;
		}
		return $orders;
	}

	public function getOrderIdByOrder(Order $order): int
	{
		$preparedStatement = $this->getSelectPrepareStatement(
			'up_order',
			['CUSTOMER_NAME' => '=', 'PHONE' => '=', 'EMAIL' => '=', 'COMMENT' => '=',
			 'STATUS' => '=', 'DATE_CREATE' => '=', 'DATE_UPDATE' => '=']
		);
		$preparedStatement->execute($this->prepareOrderAssociated($order));
		$orderData = $preparedStatement->fetch();
		return $orderData['ID'];
	}

	public function addOrder(Order $order): void
	{
		$preparedStatement = $this->getInsertPrepareStatement(
			'up_order',
			['CUSTOMER_NAME', 'PHONE', 'EMAIL', 'COMMENT', 'STATUS', 'DATE_CREATE', 'DATE_UPDATE']
		);
		$preparedStatement->execute($this->prepareOrder($order));
	}

	private function prepareOrder(Order $order): array
	{
		return [$order->getCustomerName(), $order->getPhone(), $order->getEmail(), $order->getComment(),
				$order->getStatus(), $order->getDateCreate(), $order->getDateUpdate()];
	}

	private function prepareOrderAssociated(Order $order): array
	{
		return ['CUSTOMER_NAME' => $order->getCustomerName(),
				'PHONE' => $order->getPhone(),
				'EMAIL' => $order->getEmail(),
				'COMMENT' => $order->getComment(),
				'STATUS' => $order->getStatus(),
				'DATE_CREATE' => $order->getDateCreate(),
				'DATE_UPDATE' => $order->getDateUpdate()];
	}

	public function addOrderItems(int $orderId, array $items): void
	{
		$preparedStatement = $this->getInsertPrepareStatement(
			'up_order_item',
			['ORDER_ID', 'ITEM_ID', 'COUNT']
		);
		$data = $this->prepareOrderItems($orderId, $items);
		foreach ($data as $row)
		{
			$preparedStatement->execute($row);
		}
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
