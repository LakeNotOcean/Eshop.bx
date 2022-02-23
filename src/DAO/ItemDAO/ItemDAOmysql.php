<?php

namespace Up\DAO\ItemDAO;

use PDOStatement;
use Up\Core\Database\DefaultDatabase;
use Up\DAO\AbstractDAO;
use Up\Entity\Item;
use Up\Entity\ItemDetail;
use Up\Entity\ItemsImage;
use Up\Entity\ItemsTag;
use Up\Entity\ItemType;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;

class ItemDAOmysql extends AbstractDAO implements ItemDAOInterface
{

	/**
	 * @param \Up\Core\Database\DefaultDatabase $dbConnection
	 */
	public function __construct(DefaultDatabase $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	/**
	 * @param int $offset Смещение количества объектов
	 * @param int $amountItems Количество item-ов в выборке
	 *
	 * @return array
	 */
	public function getItems(int $offset, int $amountItems): array
	{
		$result = $this->dbConnection->query($this->getItemsQuery($offset, $amountItems));
		return $this->mapItems($result);
	}

	/**
	 * @param int[] $itemIds
	 *
	 * @return array<Item>
	 */
	public function getItemsWithIds(array $itemIds): array
	{
		foreach ($itemIds as $index => $itemId)
		{
			if (!is_numeric($itemId))
			{
				$type = gettype($itemId);
				throw new \InvalidArgumentException("Item id must be int or numeric. Now: {$type}");
			}
		}

		$statement = $this->prepareItemWhereIdInRange(count($itemIds));
		$statement->execute($itemIds);
		return $this->mapItems($statement);
	}

	public function getFavoriteItems(int $userId, int $offset, int $amountItems): array
	{
		$query = $this->getFavoriteItemsQuery($userId, $offset, $amountItems);
		$result = $this->dbConnection->query($query);
		return $this->mapItems($result);
	}

	public function getFavoriteItemsAmount(int $userId): int
	{
		$query = "SELECT COUNT(USER_ID) AS `favorites_count` FROM `up_user-favorite_item`
			WHERE USER_ID = $userId GROUP BY USER_ID;";
		$result = $this->dbConnection->query($query);
		return $result->fetch()['favorites_count'] ?? 0;
	}

	public function addToFavorites(int $userId, int $favoriteItemId): void
	{
		$query = "
			INSERT INTO `up_user-favorite_item` (USER_ID, FAVORITE_ITEM_ID) 
			VALUES ($userId, $favoriteItemId);";
		$this->dbConnection->query($query);
	}

	public function removeFromFavorites(int $userId, int $favoriteItemId): void
	{
		$query = "
			DELETE FROM `up_user-favorite_item`
			WHERE USER_ID = $userId AND FAVORITE_ITEM_ID = $favoriteItemId;";
		$this->dbConnection->query($query);
	}


	public function getItemsByTypeID(int $offset,int $amountItems, int $typeID): array
	{
		$dbQuery = $this->getItemsByTypeIDQuery($offset,$amountItems,$typeID);
		$result = $this->dbConnection->prepare($dbQuery);
		$result->execute();
		$items = [];
		while ($row = $result->fetch())
		{$itemId = (int)$row['ui_ID'];
			if (!array_key_exists($itemId, $items))
			{
				$item = new Item();
				$this->mapItemCommonInfo($item, $row);
				$image = new ItemsImage();
				$this->mapItemsImageInfo($image, $row);
				$item->setMainImage($image);
				$items[$itemId] = $item;
			}
			else
			{
				$this->mapItemsImageInfo($items[$itemId]->getMainImage(), $row);
			}
		}

		return $items;
	}

	public function getItemsByQuery(int $offset, int $amountItems, string $searchQuery): array
	{
		$dbQuery = $this->getItemsQuery($offset, $amountItems, $searchQuery);
		$result = $this->dbConnection->prepare($dbQuery);
		$result->execute(["%$searchQuery%"]);
		return $this->mapItems($result);
	}

	public function getSimilarItemById(int $id, int $similarAmount): array
	{
		$dbQuery = $this->getSimilarItemByIdQuery($id,$similarAmount);
		$result = $this->dbConnection->query($dbQuery);
		return $this->mapItems($result);
	}

	protected function mapItems(PDOStatement $result): array
	{
		$items = [];
		while ($row = $result->fetch())
		{
			$itemId = (int)$row['ui_ID'];
			if (!array_key_exists($itemId, $items))
			{
				$item = new Item();
				$this->mapItemCommonInfo($item, $row);
				$image = new ItemsImage();
				$this->mapItemsImageInfo($image, $row);
				$item->setMainImage($image);
				$items[$itemId] = $item;
			}
			else
			{
				$this->mapItemsImageInfo($items[$itemId]->getMainImage(), $row);
			}
		}
		return $items;
	}


	public function getItemsMinMaxPrice(): array
	{
		$dbQuery = $this->getItemsMinMaxPriceQuery();
		$result = $this->dbConnection->query($dbQuery);
		$minPrice = 0;
		$maxPrice = 900000;
		foreach ($result as $prices)
		{
			$minPrice = $prices['MINPRICE'];
			$maxPrice = $prices['MAXPRICE'];
		}
		$price = [
			'minPrice' => $minPrice,
			'maxPrice' => $maxPrice,
		];

		return $price;
	}

	public function getItemsMinMaxPriceByItemTypes(array $typeIds): array
	{
		$dbQuery = $this->getItemsMinMaxPriceByItemTypesQuery($typeIds);
		$result = $this->dbConnection->query($dbQuery);
		$minPrice = 0;
		$maxPrice = 900000;
		foreach ($result as $prices)
		{
			(int) $minPrice = $prices['MINPRICE'];
			(int) $maxPrice = $prices['MAXPRICE'];
		}
		$price = [
			'minPrice' => $minPrice,
			'maxPrice' => $maxPrice,
		];
		return $price;
	}

	public function getItemsByFilters(
		int $offset,
		int $amountItems,
		string $query,
		string $price,
		array $tags,
		array $newSpecs,
		int $typeId,
		bool $deactivate_include
	): array
	{

		$dbQuery = $this->getItemsByFiltersQuery($offset, $amountItems,$price,$typeId, $query,  $tags, $newSpecs,$deactivate_include);
		$preparedQuery = $this->dbConnection->prepare($dbQuery);

		$executeParam = [];

		if (!empty($tags))
		{
			foreach ($tags as $tag)
			{
				$executeParam[] = $tag;
			}
		}

		if (!empty($newSpecs))
		{
			foreach ($newSpecs as $spec => $values)
			{
				$executeParam[] = $spec;
				foreach ($values as $value)
				{
					$executeParam[] = "$value";
				}

			}
		}
		if ($query !== "")
		{
			$executeParam[] = "%{$query}%";
		}


		$preparedQuery->execute($executeParam);
		$items = [];
		while ($row = $preparedQuery->fetch())
		{
			$itemId = (int)$row['ui_ID'];
			if (!array_key_exists($itemId, $items))
			{
				$item = new Item();
				$this->mapItemCommonInfo($item, $row);
				$image = new ItemsImage();
				$this->mapItemsImageInfo($image, $row);
				$item->setMainImage($image);
				$items[$itemId] = $item;
			}
			else
			{
				$this->mapItemsImageInfo($items[$itemId]->getMainImage(), $row);
			}
		}
		return $items;
	}

	public function getItemsByOrderId(int $orderId): array
	{
		$result = $this->dbConnection->query($this->getItemsByOrderIdQuery($orderId));

		$items = [];
		while ($row = $result->fetch())
		{
			$item = new Item();
			$item->setId($row['ID']);
			$item->setTitle($row['TITLE']);
			$item->setPrice($row['PRICE']);
			$items[] = $item;
		}

		return $items;
	}

	public function getItemDetailById(int $id): ItemDetail
	{
		$result = $this->dbConnection->query($this->getItemDetailByIdQuery($id));
		$item = new ItemDetail();
		while ($row = $result->fetch())
		{
			if ($item->getId() === 0)
			{
				$this->mapDetailItemInfo($item, $row);
			}
			if (!$item->hasTag($row['TAG_ID']))
			{
				$item->setTag($this->getTag($row));
			}
			if (!$item->hasSpecificationCategory($row['usc_ID']))
			{
				$item->setSpecificationCategory(
					new SpecificationCategory(
						$row['usc_ID'], $row['usc_NAME'], $row['usc_DISPLAY_ORDER']
					)
				);
			}
			if (!$item->getSpecificationCategoryById($row['usc_ID'])->hasSpecification($row['SPEC_TYPE_ID']))
			{
				$item->getSpecificationCategoryById($row['usc_ID'])->setSpecification(
					new Specification(
						$row['SPEC_TYPE_ID'], $row['ust_NAME'], $row['ust_DISPLAY_ORDER'], $row['VALUE']
					)
				);
			}
			if (!$item->hasImage($row['u_ID']))
			{
				$item->setImage($this->getItemsImage($row));
				if ($row['IS_MAIN'] and !$item->isSetMainImage())
				{
					$item->setMainImage($item->getImageById($row['u_ID']));
				}
			}
			if (!$item->getImageById($row['u_ID'])->hasSize($row['SIZE']))
			{
				$item->getImageById($row['u_ID'])->setPath($row['SIZE'], $row['SIZE_PATH']);
			}
		}

		return $item;
	}

	public function save(ItemDetail $item): ItemDetail
	{
		if ($item->getId() === 0)
		{
			return $this->create($item);
		}

		return $this->update($item);
	}

	private function update(ItemDetail $item): ItemDetail
	{
		$oldItem = $this->getItemDetailById($item->getId());

		$oldTags = $oldItem->getTags();
		$newTags = $item->getTags();

		$oldSpecsCat = $oldItem->getSpecificationCategoriesList();
		$newSpecsCat = $item->getSpecificationCategoriesList();

		$oldSpecs = $this->getSpecsFromCategory($oldSpecsCat);
		$newSpecs = $this->getSpecsFromCategory($newSpecsCat);


		if (!empty($oldTags))
		{
			$this->dbConnection->query(
				$this->getDeleteWhereAndWhereInQuery($item->getId(), array_keys($oldTags), [
					'table_name' => '`up_item-tag`',
					'item_id_name' => 'ITEM_ID',
					'other_id_name' => 'TAG_ID',
				])
			);
		}
		if (!empty($oldSpecs))
		{
			$this->dbConnection->query(
				$this->getDeleteWhereAndWhereInQuery($item->getId(), array_keys($oldSpecs), [
					'table_name' => '`up_item-spec`',
					'item_id_name' => 'ITEM_ID',
					'other_id_name' => 'SPEC_TYPE_ID',
				])
			);
		}
		if (!empty($newTags))
		{
			$this->dbConnection->query($this->getInsertTagsQuery($item->getId(), $newTags));
		}
		if (!empty($newSpecs))
		{
			$this->getInsertPrepareStatement(
				'`up_item-spec`',
				['ITEM_ID', 'SPEC_TYPE_ID', 'VALUE'],
				count($newSpecs)
			)->execute($this->getSpecsExecuteArray($item->getId(), $newSpecs));
		}

		$statement = $this->getUpdatePrepareStatement(
			'up_item',
			[
				'TITLE',
				'PRICE',
				'SHORT_DESC',
				'FULL_DESC',
				'SORT_ORDER',
				'ACTIVE',
				'ITEM_TYPE_ID',
				'DATE_UPDATE',
			],
			'ID'
		);
		$statement->execute([
			$item->getTitle(),
			$item->getPrice(),
			$item->getShortDescription(),
			$item->getFullDescription(),
			$item->getSortOrder(),
			$item->getIsActive(),
			$item->getItemType()->getId(),
			date('Y-m-d H:i:s'),
			$item->getId()
		]);

		return $item;
	}

	private function create(ItemDetail $item): ItemDetail
	{
		$this->getInsertPrepareStatement('up_item', [
			'TITLE',
			'PRICE',
			'SHORT_DESC',
			'FULL_DESC',
			'SORT_ORDER',
			'ACTIVE',
			'DATE_CREATE',
			'DATE_UPDATE',
			'ITEM_TYPE_ID',
		])->execute([
			$item->getTitle(),
			$item->getPrice(),
			$item->getShortDescription(),
			$item->getFullDescription(),
			$item->getSortOrder(),
			$item->getIsActive(),
			date('Y-m-d H:i:s'),
			date('Y-m-d H:i:s'),
			$item->getItemType()->getId(),
		]);
		$id = $this->dbConnection->lastInsertId();
		$item->setId($id);
		$tags = $item->getTags();

		$specsCat = $item->getSpecificationCategoriesList();

		$specs = $this->getSpecsFromCategory($specsCat);

		$this->dbConnection->query($this->getInsertTagsQuery($id, $tags));

		$this->getInsertPrepareStatement(
			'`up_item-spec`',
			['ITEM_ID', 'SPEC_TYPE_ID', 'VALUE'],
			count($specs)
		)->execute($this->getSpecsExecuteArray($item->getId(), $specs));

		return $item;
	}

	/**
	 * @param array<int,SpecificationCategory> $categories
	 *
	 * @return array
	 */
	private function getSpecsFromCategory(array $categories): array
	{
		$specs = [];
		foreach ($categories as $cat)
		{
			$category = $cat->getSpecifications();
			foreach ($category as $id => $spec)
			{
				$specs[$id] = $spec;
			}
		}

		return $specs;
	}

	public function getItemsAmount(string $searchQuery = ''): int
	{
		$query = 'SELECT count(1) AS num_items FROM up_item WHERE ACTIVE = 1';
		if ($searchQuery !== '')
		{
			$query .= " AND TITLE LIKE '%$searchQuery%' ";
		}
		$result = $this->dbConnection->query($query);

		return $result->fetch()['num_items'];
	}

	public function getItemsAmountByTypeId(int $typeId,string $searchQuery = ''): int
	{
		$query = "SELECT count(1) AS num_items FROM up_item WHERE ACTIVE = 1 AND ITEM_TYPE_ID = {$typeId}";
		if ($searchQuery !== '')
		{
			$query .= " AND TITLE LIKE '%$searchQuery%' ";
		}
		$result = $this->dbConnection->query($query);

		return $result->fetch()['num_items'];
	}

	public function deactivateItem(int $id): void
	{
		$this->dbConnection->query("UPDATE up_item SET ACTIVE = 0 WHERE ID={$id}");
	}

	public function activateItem(int $id): void
	{
		$this->dbConnection->query("UPDATE up_item SET ACTIVE = 1 WHERE ID={$id}");
	}

	public function deleteItem(int $id): void
	{
		$this->dbConnection->query("DELETE FROM up_item WHERE ID={$id}");
	}

	public function updateCommonInfo(Item $item): Item
	{
		$statement = $this->dbConnection->prepare($this->getUpdateCommonInfoQuery());
		$executeParam = [
			$item->getTitle(),
			$item->getPrice(),
			$item->getShortDescription(),
			$item->getSortOrder(),
			date('Y-m-d H:i:s'),
			$item->getId(),
		];
		$statement->execute($executeParam);

		return $item;
	}

	private function getUpdateCommonInfoQuery(): string
	{
		return "UPDATE up_item 
				SET TITLE=?, 
				    PRICE=?, 
				    SHORT_DESC=?, 
				    SORT_ORDER=?,
				    DATE_UPDATE=?
				WHERE ID=?";
	}

	public function getItemsAmountByFilters(string $query, string $price, array $tags, array $newSpecs,int $typeId,bool $deactivate_include = false)
	{
		$dbQuery = $this->getItemsAmountByFiltersQuery($query, $price, $tags, $newSpecs,$typeId ,$deactivate_include);
		$preparedQuery = $this->dbConnection->prepare($dbQuery);

		$executeParam = [];

		if (!empty($tags))
		{
			foreach ($tags as $tag)
			{
				$executeParam[] = $tag;
			}
		}

		if (!empty($newSpecs))
		{
			foreach ($newSpecs as $spec => $values)
			{
				$executeParam[] = $spec;
				foreach ($values as $value)
				{
					$executeParam[] = "$value";
				}

			}
		}
		if ($query !== "")
		{
			$executeParam[] = "%{$query}%";
		}
		$preparedQuery->execute($executeParam);


		return $preparedQuery->fetch()['num_items'];
	}

	private function getItemsByTypeIDQuery(int $offset, int $amountItems, int $typeID): string
	{
		$query = "SELECT ID AS ID FROM up_item WHERE ITEM_TYPE_ID = ".$typeID . " LIMIT {$offset}, {$amountItems}";
		$query = $this->getQueryGetItemsById($query);
		return $query;
	}

	private function getSimilarItemByIdQuery(int $itemID, int $similarAmount): string
	{
		$getIDquery = "Select ID, count(1) as COUNT from (SELECT
	                                   ITEM_ID as ID,
	                                   TAG_ID
                                   FROM `up_item-tag`
                                   WHERE ITEM_ID != "
			. $itemID
			. " AND TAG_ID in (SELECT TAG_ID FROM `up_item-tag` WHERE ITEM_ID = "
			. $itemID
			. ")) as IITI group by ID
ORDER BY COUNT DESC
LIMIT "
			. $similarAmount
			. " ";
		$query = $this->getQueryGetItemsById($getIDquery);

		return $query;
	}

	private function getItemsQuery(int $offset, int $amountItems, $searchQuery = ''): string
	{
		return "SELECT ui.ID as ui_ID,
					   TITLE as TITLE,
					   PRICE as PRICE,
					   SORT_ORDER as SORT_ORDER,
					   SHORT_DESC as SHORT_DESC,
					   ACTIVE as ACTIVE,
					   uoi.ID as ORIGINAL_IMAGE_ID,
					   uoi.PATH as ORIGINAL_IMAGE_PATH,
					   uoi.IS_MAIN as ORIGINAL_IMAGE_IS_MAIN,
					   uiws.PATH as IMAGE_WITH_SIZE_PATH,
					   uiws.SIZE as IMAGE_WITH_SIZE_SIZE
				FROM up_item ui
						 INNER JOIN up_original_image uoi on ui.ID = uoi.ITEM_ID AND uoi.IS_MAIN = 1
						 INNER JOIN up_image_with_size uiws on uoi.ID = uiws.ORIGINAL_IMAGE_ID
				WHERE ui.ID IN (
					select uiI.ID from (
										   SELECT ID FROM up_item ui2 WHERE ACTIVE = 1 AND TITLE LIKE '%{$searchQuery}%' ORDER BY ui2.SORT_ORDER desc, ui2.ID LIMIT {$offset}, {$amountItems}
		
									   ) as uiI
				)
				ORDER BY ui.SORT_ORDER desc, ui.ID;";
	}

	private function prepareItemWhereIdInRange(int $elementsCount): PDOStatement
	{
		$query = '
			SELECT ui.ID as ui_ID,
					   TITLE as TITLE,
					   PRICE as PRICE,
					   SORT_ORDER as SORT_ORDER,
					   SHORT_DESC as SHORT_DESC,
					   ACTIVE as ACTIVE,
					   uoi.ID as ORIGINAL_IMAGE_ID,
					   uoi.PATH as ORIGINAL_IMAGE_PATH,
					   uoi.IS_MAIN as ORIGINAL_IMAGE_IS_MAIN,
					   uiws.PATH as IMAGE_WITH_SIZE_PATH,
					   uiws.SIZE as IMAGE_WITH_SIZE_SIZE
				FROM up_item ui
						 INNER JOIN up_original_image uoi on ui.ID = uoi.ITEM_ID AND uoi.IS_MAIN = 1
						 INNER JOIN up_image_with_size uiws on uoi.ID = uiws.ORIGINAL_IMAGE_ID
				';
		if ($elementsCount === 0)
		{
			return $this->dbConnection->prepare($query . 'where ui.ID = -1');
		}
		return $this->dbConnection->prepare($query . 'WHERE ui.ID IN'. $this->getPreparedGroup($elementsCount));
	}

	private function getFavoriteItemsQuery(int $userId, int $offset, int $amountItems): string
	{
		return "
		SELECT ui.ID as ui_ID,
			TITLE as TITLE,
			PRICE as PRICE,
			SORT_ORDER as SORT_ORDER,
			SHORT_DESC as SHORT_DESC,
			ACTIVE as ACTIVE,
			uoi.ID as ORIGINAL_IMAGE_ID,
			uoi.PATH as ORIGINAL_IMAGE_PATH,
			uoi.IS_MAIN as ORIGINAL_IMAGE_IS_MAIN,
			uiws.PATH as IMAGE_WITH_SIZE_PATH,
			uiws.SIZE as IMAGE_WITH_SIZE_SIZE
		FROM up_item ui
		INNER JOIN up_original_image uoi on ui.ID = uoi.ITEM_ID AND uoi.IS_MAIN = 1
		INNER JOIN up_image_with_size uiws on uoi.ID = uiws.ORIGINAL_IMAGE_ID
		WHERE ui.ID IN (
			select ufi.ID from (
				SELECT FAVORITE_ITEM_ID as ID FROM `up_user-favorite_item`
				WHERE USER_ID = $userId
				" . ($offset >= 0 ? "LIMIT $offset, $amountItems" : "") . ") as ufi)
		ORDER BY ui.SORT_ORDER desc, ui.ID;";
	}

	private function getItemsByOrderIdQuery(int $orderId): string
	{
		return "SELECT * FROM up_item
                INNER JOIN `up_order-item` on ITEM_ID = ID
				WHERE ORDER_ID = $orderId;";
	}

	private function getItemDetailByIdQuery(int $id): string
	{
		return "SELECT ui.ID as ui_ID,
						ui.TITLE as TITLE,
					   PRICE as PRICE,
					   up_tag.ID as TAG_ID,
					   up_tag.TITLE as TAG,
					   SORT_ORDER as SORT_ORDER,
					   SHORT_DESC as SHORT_DESC,
					   FULL_DESC as FULL_DESC,
					   ACTIVE as ACTIVE, ui.ITEM_TYPE_ID as ITEM_TYPE_ID, uit.NAME as TYPE_NAME,
					   uis.SPEC_TYPE_ID as SPEC_TYPE_ID, uis.VALUE as VALUE, ust.NAME as ust_NAME, ust.DISPLAY_ORDER as ust_DISPLAY_ORDER, usc.ID as usc_ID,
						usc.NAME as usc_NAME, usc.DISPLAY_ORDER as usc_DISPLAY_ORDER, u.ID as u_ID, u.IS_MAIN as IS_MAIN, u.PATH as ORIGINAL_PATH, uiws.PATH as SIZE_PATH, uiws.SIZE as SIZE
				FROM up_item ui inner join up_item_type uit on ui.ITEM_TYPE_ID = uit.ID AND ui.ID={$id}
                LEFT JOIN `up_item-tag` ut on ut.ITEM_ID = ui.ID
                LEFT JOIN up_tag on up_tag.ID=ut.TAG_ID
                INNER JOIN `up_item-spec` uis on ui.ID = uis.ITEM_ID
                INNER JOIN up_spec_type ust on uis.SPEC_TYPE_ID = ust.ID
                INNER JOIN up_spec_category usc on ust.SPEC_CATEGORY_ID = usc.ID
                INNER JOIN up_original_image u on ui.ID = u.ITEM_ID
				INNER JOIN up_image_with_size uiws on u.ID = uiws.ORIGINAL_IMAGE_ID;";
	}

	private function getDeleteWhereAndWhereInQuery(int $id, array $ids, array $table): string
	{
		$whereIn = '(' . implode(',', $ids) . ')';

		return "DELETE FROM {$table['table_name']} WHERE {$table['item_id_name']}={$id} AND {$table['other_id_name']} IN {$whereIn};";
	}

	private function getInsertTagsQuery(int $id, array $tags): string
	{
		$insert = implode(
			',',
			array_map(function(ItemsTag $tag) use ($id) {
				return "({$id},{$tag->getId()})";
			}, $tags)
		);

		return "INSERT INTO `up_item-tag` (ITEM_ID, TAG_ID) VALUES {$insert};";
	}

	/**
	 * @param int $itemId
	 * @param array<int, Specification> $specs
	 *
	 * @return array
	 */
	private function getSpecsExecuteArray(int $itemId, array $specs): array
	{
		$executeArray = [];
		foreach ($specs as $spec)
		{
			$executeArray[] = $itemId;
			$executeArray[] = $spec->getId();
			$executeArray[] = $spec->getValue();
		}

		return $executeArray;
	}

	private function mapItemCommonInfo(Item $item, array $row)
	{
		$item->setId($row['ui_ID']);
		$item->setTitle($row['TITLE']);
		$item->setPrice($row['PRICE']);
		$item->setShortDescription($row['SHORT_DESC']);
		$item->setSortOrder($row['SORT_ORDER']);
		$item->setIsActive($row['ACTIVE']);
	}

	private function mapDetailItemInfo(ItemDetail $item, array $row)
	{
		$this->mapItemCommonInfo($item, $row);
		$item->setFullDescription($row['FULL_DESC']);
		$item->setItemType(new ItemType($row['ITEM_TYPE_ID'], $row['TYPE_NAME']));
	}

	private function mapItemsImageInfo(ItemsImage $image, array $row)
	{
		$image->setId($row['ORIGINAL_IMAGE_ID']);
		$image->setPath($row['IMAGE_WITH_SIZE_SIZE'], $row['IMAGE_WITH_SIZE_PATH']);
		$image->setOriginalImagePath($row['ORIGINAL_IMAGE_PATH']);
		$image->setIsMain($row['ORIGINAL_IMAGE_IS_MAIN']);
	}

	private function getTag(array $row): ItemsTag
	{
		$tag = new ItemsTag();
		$tag->setId($row['TAG_ID']);
		$tag->setName($row['TAG']);

		return $tag;
	}

	private function getItemsImage(array $row): ItemsImage
	{
		$image = new ItemsImage();
		$image->setId($row['u_ID']);
		$image->setIsMain($row['IS_MAIN']);
		$image->setOriginalImagePath($row['ORIGINAL_PATH']);

		return $image;
	}

	public function isItemActive(int $itemId): bool
	{
		$query = "select ACTIVE from up_item where ID = {$itemId}";

		$result = $this->dbConnection->query($query)->fetch($this->dbConnection::FETCH_ASSOC);
		if ($result === false)
		{
			return false;
		}

		return $result['ACTIVE'];
	}

	private function getItemsMinMaxPriceByItemTypesQuery(array $typeIds) :string
	{
		$query = "SELECT MIN(PRICE) AS MINPRICE,
		MAX(PRICE) AS MAXPRICE
		FROM up_item
		 ";
		if (empty($typeIds))
		{
			return $query;
		}
		$where = [];
		foreach ($typeIds as $typeId)
		{
			$where = [" ITEM_TYPE_ID = ".(int) $typeId." "];
		}
		$query .= " WHERE ".implode(' OR ', $where);
		return $query;
	}

	private function getItemsMinMaxPriceQuery() :string
	{
		$query = "SELECT MIN(PRICE) AS MINPRICE,
		MAX(PRICE) AS MAXPRICE
		FROM up_item";

		return $query;
	}

	private function getItemsByFiltersQuery(
		int $offset,
		int $amountItems,
		string $price,
		int $typeId,
		string $searchQuery,
		array $tags,
		array $newSpecs,
		bool $deactivate_include
	): string
	{
		$query = "SELECT ui.ID as ui_ID,
					   TITLE as TITLE,
					   PRICE as PRICE,
					   SORT_ORDER as SORT_ORDER,
					   SHORT_DESC as SHORT_DESC,
					   ACTIVE as ACTIVE,
					   uoi.ID as ORIGINAL_IMAGE_ID,
					   uoi.PATH as ORIGINAL_IMAGE_PATH,
					   uoi.IS_MAIN as ORIGINAL_IMAGE_IS_MAIN,
					   uiws.PATH as IMAGE_WITH_SIZE_PATH,
					   uiws.SIZE as IMAGE_WITH_SIZE_SIZE
				FROM up_item ui
						 INNER JOIN up_original_image uoi on ui.ID = uoi.ITEM_ID AND uoi.IS_MAIN = 1
						 INNER JOIN up_image_with_size uiws on uoi.ID = uiws.ORIGINAL_IMAGE_ID
				WHERE ui.ID IN (
					select uiI.ID from (";
		$query .= "
	SELECT DISTINCT 
	ui.ID as ID
FROM up_item as ui";
		if (!empty($tags))
		{
			$query .= " INNER join (select ITEM_ID,
    TAG_ID
FROM
(select
	upt.ITEM_ID as ITEM_ID,
    upt.TAG_ID as TAG_ID,
    COUNT(TAG_ID) as COUNT
FROM `up_item-tag` as upt
where ";
			$where = [];
			foreach ($tags as $tag)
			{
				$where[] = 'TAG_ID = ' . "?";
			}
			$query .= implode(' OR ', $where);
			$query .= "
group by ITEM_ID
) as l
WHERE COUNT = ";
			$query .= count($tags);
			$query .= ") as uig on uig.ITEM_ID = ID";
		}
		if (!empty($newSpecs))
		{
			$query .= "
INNER JOIN (select
	ITEM_ID
FROM
(select
	 ITEM_ID as ITEM_ID,
	 COUNT(VALUE) as COUNT
 FROM `up_item-spec`
 WHERE ";
			$where = [];
			foreach ($newSpecs as $spec => $values)
			{
				$inParam = '';
				foreach ($values as $value)
				{
					$inParam .= " ?,";
				}
				$inParam = substr($inParam,0,-1);
				$where[] = "(SPEC_TYPE_ID = " . "?" . " AND VALUE IN (" . $inParam . "))";
			}
			$query .= implode(' OR ', $where);
			$query .= " GROUP BY ITEM_ID) as ls
WHERE COUNT =";
			$query .= count($newSpecs);
			$query .= ") as uis on uis.ITEM_ID = ID";
		}
		if (!($price === ""))

		{
			$query .= "
INNER JOIN (select ID as ITEM_ID,
                   PRICE as PRICE
            FROM up_item
            WHERE ";
			$minMaxPrice = explode('-', $price);
			$query .= 'PRICE >= ' . (int) $minMaxPrice[0] . ' AND PRICE <= ' . (int) $minMaxPrice[1];
			$query .= ") as uip on uip.ITEM_ID = ID";
		}
		if (!($searchQuery === ""))
		{
			$query .= "
INNER JOIN (select ID as ITEM_ID,
                   TITLE as TITLE
            FROM up_item
            WHERE TITLE LIKE ?) as uit on uit.ITEM_ID = ID";
		}
		$query .=" WHERE 1 ";
		if(!$deactivate_include)
		{
			$query .= "\n AND ACTIVE = 1";
		}
		if ($typeId !== 0)
		{
			$query .= " AND ITEM_TYPE_ID = {$typeId} ";
		}
		$query .= " ORDER BY ui.SORT_ORDER, ID
		LIMIT {$offset}, {$amountItems}";
		$query .= ") as uiI
				)
				ORDER BY ui.SORT_ORDER desc, ui.ID;
";

		return $query;
	}

	private function getQueryGetItemsById(string $queryId): string
	{
		$query = "SELECT ui.ID as ui_ID,
					   TITLE as TITLE,
					   PRICE as PRICE,
					   SORT_ORDER as SORT_ORDER,
					   SHORT_DESC as SHORT_DESC,
					   ACTIVE as ACTIVE,
					   uoi.ID as ORIGINAL_IMAGE_ID,
					   uoi.PATH as ORIGINAL_IMAGE_PATH,
					   uoi.IS_MAIN as ORIGINAL_IMAGE_IS_MAIN,
					   uiws.PATH as IMAGE_WITH_SIZE_PATH,
					   uiws.SIZE as IMAGE_WITH_SIZE_SIZE
				FROM up_item ui
						 INNER JOIN up_original_image uoi on ui.ID = uoi.ITEM_ID AND uoi.IS_MAIN = 1
						 INNER JOIN up_image_with_size uiws on uoi.ID = uiws.ORIGINAL_IMAGE_ID
				WHERE ui.ID IN (
	select uiI.ID from (";

		$query .= $queryId;
		$query .= ") as uiI
				)
				ORDER BY ui.SORT_ORDER desc, ui.ID;";

		return $query;
	}

	private function getItemsByPriceQuery($minPrice, $maxPrice): string
	{
		$query = "SELECT ID FROM up_item WHERE PRICE > ".$minPrice." AND PRICE < ".$maxPrice." ";
		$query = $this->getQueryGetItemsById($query);
		return $query;
	}


	private function getItemsAmountByFiltersQuery(
		string $searchQuery,
		string $price,
		array $tags,
		array $newSpecs,
		int $typeId,
		bool $deactivate_include):string
	{

		$query = "
	SELECT DISTINCT 
	count(1) as num_items
FROM up_item as ui";
		if (!empty($tags))
		{
			$query .= " INNER join (select ITEM_ID,
    TAG_ID
FROM
(select
	upt.ITEM_ID as ITEM_ID,
    upt.TAG_ID as TAG_ID,
    COUNT(TAG_ID) as COUNT
FROM `up_item-tag` as upt
where ";
			$where = [];
			foreach ($tags as $tag)
			{
				$where[] = 'TAG_ID = ' . "?";
			}
			$query .= implode(' OR ', $where);
			$query .= "
group by ITEM_ID
) as l
WHERE COUNT = ";
			$query .= count($tags);
			$query .= ") as uig on uig.ITEM_ID = ID";
		}
		if (!empty($newSpecs))
		{
			$query .= "
INNER JOIN (select
	ITEM_ID
FROM
(select
	 ITEM_ID as ITEM_ID,
	 COUNT(VALUE) as COUNT
 FROM `up_item-spec`
 WHERE ";
			$where = [];
			foreach ($newSpecs as $spec => $values)
			{
				$inParam = '';
				foreach ($values as $value)
				{
					$inParam .= " ?,";
				}
				$inParam = substr($inParam,0,-1);
				$where[] = "(SPEC_TYPE_ID = " . "?" . " AND VALUE IN (" . $inParam . "))";
			}
			$query .= implode(' OR ', $where);
			$query .= " GROUP BY ITEM_ID) as ls
WHERE COUNT =";
			$query .= count($newSpecs);
			$query .= ") as uis on uis.ITEM_ID = ID";
		}
		if (!($price === ""))

		{
			$query .= "
INNER JOIN (select ID as ITEM_ID,
                   PRICE as PRICE
            FROM up_item
            WHERE ";
			$minMaxPrice = explode('-', $price);
			$query .= 'PRICE >= ' . (int) $minMaxPrice[0] . ' AND PRICE <= ' . (int) $minMaxPrice[1];
			$query .= ") as uip on uip.ITEM_ID = ID";
		}
		if (!($searchQuery === ""))
		{
			$query .= "
INNER JOIN (select ID as ITEM_ID,
                   TITLE as TITLE
            FROM up_item
            WHERE TITLE LIKE ?) as uit on uit.ITEM_ID = ID";
		}
		$query .="
		WHERE 1 ";
		if(!$deactivate_include)
		{
			$query .= "
			AND ACTIVE = 1";
		}
		if ($typeId !== 0)
		{
			$query .= " AND ITEM_TYPE_ID = {$typeId} ";
		}
		return $query;
	}

}
