<?php

namespace Up\DAO\OrderDAO;

use Up\Core\Database\DefaultDatabase;
use Up\DAO\AbstractDAO;
use Up\Entity\Item;
use Up\Entity\ItemsImage;
use Up\Entity\Order\Order;
use Up\Entity\Order\OrderStatus;
use Up\Entity\User\User;
use Up\Entity\User\UserEnum;
use Up\Entity\User\UserRole;

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
			$order->setDateCreate(\DateTime::createFromFormat('Y-m-d H:i:s', $row['DATE_CREATE']));
			$order->setDateUpdate(\DateTime::createFromFormat('Y-m-d H:i:s', $row['DATE_UPDATE']));
			$orders[] = $order;
		}
		return $orders;
	}

	public function getOrdersByUserId(int $offset, int $amountItems, int $userId): array
	{
		$statement = $this->getSelectOrdersByUserIdStatement($this->getOrdersIdsByUserIdQuery($offset, $amountItems));
		$statement->execute([$userId]);
		return $this->mapOrders($statement);
	}

	public function getAmountOrdersByUserId(int $userId): int
	{
		$statement = $this->dbConnection->prepare("SELECT COUNT(*) as COUNT FROM up_order uo
														 WHERE USER_ID = ?");
		$statement->execute([$userId]);
		return $statement->fetch()['COUNT'];
	}

	private function mapOrders(\PDOStatement $statement): array
	{
		$orders = [];
		while ($row = $statement->fetch())
		{
			if (!array_key_exists($row['o_id'], $orders))
			{
				$orders[$row['o_id']] = $this->mapOrderCommonInfo($row);
			}
			$order = $orders[$row['o_id']];
			if (!$order->issetUser())
			{
				$order->setUser($this->mapUser($row));
			}
			if (!$order->hasItem($row['i_id']))
			{
				$order->addItem($this->mapItemCommonInfo($row), $row['i_count']);
			}
			$item = $order->getItem($row['i_id']);
			if (!$item->isSetMainImage())
			{
				$item->setMainImage($this->mapImageCommonInfo($row));
			}
			if(!$item->getMainImage()->hasSize($row['img_size']))
			{
				$item->getMainImage()->setPath($row['img_size'], $row['img_size_path']);
			}
		}
		return $orders;
	}

	private function mapImageCommonInfo($row): ItemsImage
	{
		$image = new ItemsImage();
		$image->setId($row['img_id']);
		$image->setIsMain($row['img_is_main']);
		$image->setOriginalImagePath($row['img_orig_path']);
		return $image;
	}

	private function mapUser($row): User
	{
		$role = new UserRole(new UserEnum($row['role_name']));

		return new User(
			$row['u_id'],
			$row['u_login'],
			$role,
			$row['u_email'],
			$row['u_phone'],
			$row['u_first_name'],
			$row['u_second_name']
		);
	}

	private function mapItemCommonInfo($row): Item
	{
		$item = new Item();
		$item->setId($row['i_id']);
		$item->setTitle($row['i_title']);
		$item->setPrice($row['i_price']);
		$item->setShortDescription($row['i_short_desc']);
		$item->setSortOrder($row['i_sort_order']);
		$item->setIsActive($row['i_active']);

		return $item;
	}

	private function mapOrderCommonInfo($row): Order
	{
		$order = new Order($row['o_customer_name'], $row['o_phone'], $row['o_email'], $row['o_comment']);
		$order->setDateCreate(\DateTime::createFromFormat('Y-m-d H:i:s', $row['o_date_create']));
		$order->setDateUpdate(\DateTime::createFromFormat('Y-m-d H:i:s', $row['o_date_update']));
		$order->setStatus(OrderStatus::from($row['o_status']));

		return $order;
	}

	private function getSelectOrdersByUserIdStatement(string $ids): \PDOStatement
	{
		$query = "SELECT uo.ID o_id,
                  uo.CUSTOMER_NAME o_customer_name,
                  uo.STATUS o_status,
                  uo.PHONE o_phone,
                  uo.EMAIL o_email,
                  uo.COMMENT o_comment,
                  uo.DATE_CREATE o_date_create,
                  uo.DATE_UPDATE o_date_update,
                  ur.NAME role_name,
                  uu.ID u_id,
                  uu.EMAIL u_email,
                  uu.PHONE u_phone,
                  uu.SECOND_NAME u_second_name,
                  uu.LOGIN u_login,
                  uu.FIRST_NAME u_first_name,
                  ui.ID i_id,
                  ui.TITLE i_title,
                  ui.PRICE i_price,
                  ui.SHORT_DESC i_short_desc,
                  ui.SORT_ORDER i_sort_order,
                  ui.ACTIVE i_active,
                  `uo-i`.COUNT i_count,
                  uoi.ID img_id,
                  uoi.IS_MAIN img_is_main,
                  uoi.PATH img_orig_path,
                  uiws.SIZE img_size,
                  uiws.PATH img_size_path
                  FROM up_order uo
                  INNER JOIN up_user uu on uo.USER_ID = uu.ID AND uo.ID IN ($ids)
                  INNER JOIN up_role ur on uu.ROLE_ID = ur.ID
                  INNER JOIN `up_order-item` `uo-i` on uo.ID = `uo-i`.ORDER_ID
                  INNER JOIN up_item ui on `uo-i`.ITEM_ID = ui.ID
                  INNER JOIN up_original_image uoi on ui.ID = uoi.ITEM_ID AND uoi.IS_MAIN = 1
                  INNER JOIN up_image_with_size uiws on uoi.ID = uiws.ORIGINAL_IMAGE_ID";

		return $this->dbConnection->prepare($query);
	}

	private function getOrdersIdsByUserIdQuery(int $offset, int $amount): string
	{
		return "SELECT * FROM (SELECT ID FROM up_order uo
				WHERE USER_ID = ?
				ORDER BY uo.DATE_CREATE
				LIMIT $offset, $amount) ids";
	}

	public function existUsersFinishedOrderByItemId(int $userId, int $itemId): bool
	{
		$statement = $this->dbConnection->prepare(
			"SELECT 1 FROM up_order 
                                                         INNER JOIN `up_order-item` `uo-i` on up_order.ID = `uo-i`.ORDER_ID 
														 WHERE USER_ID=? AND ITEM_ID=? AND STATUS='DONE' LIMIT 1"
		);
		$statement->execute([$userId, $itemId]);
		return (bool)$statement->fetch();
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
		$preparedStatement = $this->getInsertPrepareStatement('up_order',
															  [
																  'CUSTOMER_NAME',
																  'PHONE',
																  'EMAIL',
																  'COMMENT',
																  'STATUS',
																  'DATE_CREATE',
																  'DATE_UPDATE',
																  'USER_ID',
															  ]);
		$preparedStatement->execute($this->prepareOrder($order));
	}

	private function prepareOrder(Order $order): array
	{
		$userId = $order->getUser()->getId();
		return [
			$order->getCustomerName(),
			$order->getPhone(),
			$order->getEmail(),
			$order->getComment(),
			$order->getStatus(),
			$order->getDateCreate()->format('Y-m-d H:i:s'),
			$order->getDateUpdate()->format('Y-m-d H:i:s'),
			($userId === 0 ? null : $userId),
		];
	}

	public function addOrderItems(int $orderId, array $items): void
	{
		$preparedStatement = $this->getInsertPrepareStatement('`up_order-item`', ['ORDER_ID', 'ITEM_ID', 'COUNT']);
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

	public function deleteOrder(int $orderId): void
	{
		$query = "DELETE FROM up_order WHERE ID = $orderId;";
		$this->dbConnection->query($query);
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
