<?php

namespace Up\DAO\ItemDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\Item;
use Up\Entity\ItemDetail;
use Up\Entity\ItemsImage;
use Up\Entity\ItemsTag;
use Up\Entity\ItemType;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;


class ItemDAOmysql implements ItemDAOInterface
{
	private $DBConnection;

	/**
	 * @param \Up\Core\Database\DefaultDatabase $DBConnection
	 */
	public function __construct(DefaultDatabase $DBConnection)
	{
		$this->DBConnection = $DBConnection;
	}

	public function getItems(int $offset, int $amountItems): array
	{
		$result = $this->DBConnection->query($this->getItemsQuery($offset, $amountItems));
		$items = [];
		while ($row = $result->fetch())
		{
			$item = new Item();
			$this->mapItemCommonInfo($item, $row);
			$image = new ItemsImage();
			$this->mapItemsImageInfo($image, $row);
			$item->setMainImage($image);
			$items[] = $item;
		}

		return $items;
	}


	public function getItemsByQuery(int $offset, int $amountItems, string $searchQuery): array
	{
		$dbQuery = $this->getItemsQuery($offset, $amountItems, $searchQuery);
		$result = $this->DBConnection->prepare($dbQuery);
		$result->execute(["%$searchQuery%"]);
		$items = [];
		foreach ($result as $row)
		{
			$item = new Item();
			$this->mapItemCommonInfo($item, $row);
			$image = new ItemsImage();
			$this->mapItemsImageInfo($image, $row);
			$item->setMainImage($image);
			$items[] = $item;
		}

		return $items;
	}

	public function getItemsMinMaxPrice()
	{
		$dbQuery = $this->getItemsMinMaxPriceQuery();
		$result = $this->DBConnection->query($dbQuery);
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

	public function getItemsByFilters(int $offset, int $amountItems,string $query,string $price,array $tags,array $specs): array
	{
		$dbQuery = $this->getItemsByFiltersQuery($offset, $amountItems,$query,$price,$tags,$specs);
		$result = $this->DBConnection->query($dbQuery);
		$items = [];
		foreach ($result as $row)
		{
			$item = new Item();
			$this->mapItemCommonInfo($item, $row);
			$image = new ItemsImage();
			$this->mapItemsImageInfo($image, $row);
			$item->setMainImage($image);
			$items[] = $item;
		}
		return $items;
	}

	public function getItemsByOrderId(int $orderId): array
	{
		$result = $this->DBConnection->query($this->getItemsByOrderIdQuery($orderId));

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
		$result = $this->DBConnection->query($this->getItemDetailByIdQuery($id));
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

		$oldImages = $oldItem->getImages();
		$newImages = $item->getImages();

		if (!empty($oldTags))
		{
			$this->DBConnection->query(
				$this->getDeleteWhereAndWhereInQuery($item->getId(), array_keys($oldTags), [
					'table_name' => 'up_item_tag',
					'item_id_name' => 'ITEM_ID',
					'other_id_name' => 'TAG_ID',
				])
			);
		}
		if (!empty($oldSpecs))
		{
			$this->DBConnection->query(
				$this->getDeleteWhereAndWhereInQuery($item->getId(), array_keys($oldSpecs), [
					'table_name' => 'up_item_spec',
					'item_id_name' => 'ITEM_ID',
					'other_id_name' => 'SPEC_TYPE_ID',
				])
			);
		}
		if (!empty($oldImages))
		{
			$this->DBConnection->query(
				$this->getDeleteWhereAndWhereInQuery($item->getId(), array_keys($oldImages), [
					'table_name' => 'up_image',
					'item_id_name' => 'ITEM_ID',
					'other_id_name' => 'ID',
				])
			);
		}
		if (!empty($newTags))
		{
			$this->DBConnection->query($this->getInsertTagsQuery($item->getId(), $newTags));
		}
		if (!empty($newSpecs))
		{
			$this->DBConnection->query($this->getInsertSpecsQuery($item->getId(), $newSpecs));
		}
		if (!empty($newImages))
		{
			$this->DBConnection->query($this->getInsertImagesQuery($item->getId(), $newImages));
		}
		$this->DBConnection->query($this->getUpdateItemQuery($item));

		return $item;
	}

	private function create(ItemDetail $item): ItemDetail
	{
		$this->DBConnection->query($this->getInsertItemQuery($item));
		$id = $this->DBConnection->lastInsertId();
		$item->setId($id);
		$tags = $item->getTags();

		$specsCat = $item->getSpecificationCategoriesList();

		$specs = $this->getSpecsFromCategory($specsCat);

		$images = $item->getImages();

		$this->DBConnection->query($this->getInsertTagsQuery($id, $tags));
		$this->DBConnection->query($this->getInsertSpecsQuery($id, $specs));
		if (!empty($images))
		{
			$this->DBConnection->query($this->getInsertImagesQuery($id, $images));
		}

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
		$result = $this->DBConnection->query($query);

		return $result->fetch()['num_items'];
	}


	public function deactivateItem(int $id): void
	{
		$this->DBConnection->query("UPDATE up_item SET ACTIVE = 0 WHERE ID={$id}");
	}

	public function updateCommonInfo(Item $item): Item
	{
		$this->DBConnection->query($this->getUpdateCommonInfoQuery($item));
		return $item;
	}

	private function getUpdateCommonInfoQuery(Item $item): string
	{
		$date = date('Y-m-d H:i:s');
		return "UPDATE up_item 
				SET TITLE='{$item->getTitle()}', 
				    PRICE={$item->getPrice()}, 
				    SHORT_DESC='{$item->getShortDescription()}', 
				    SORT_ORDER={$item->getSortOrder()},
				    DATE_UPDATE='{$date}'
				WHERE ID={$item->getId()}";
	}

	public function getItemsAmountByFilters(string $query,string $price,array $tags,array $specs)
	{
		$query = $this->getItemsAmountByFiltersQuery($query,$price,$tags,$specs);
		$result = $this->DBConnection->query($query);
		return $result->fetch()['num_items'];
	}



	private function getItemsQuery(int $offset, int $amountItems, string $searchQuery = ''): string
	{
		$result = "SELECT ui.ID as ui_ID,
                        TITLE as TITLE,
                        PRICE as PRICE,
                        SORT_ORDER as SORT_ORDER,
                        SHORT_DESC as SHORT_DESC,
                        ACTIVE as ACTIVE,
                        u.ID IMAGE_ID,
                        u.PATH IMAGE_PATH,
                        u.IS_MAIN IMAGE_IS_MAIN
				FROM up_item ui
				INNER JOIN up_image u on ui.ID = u.ITEM_ID AND u.IS_MAIN = 1
				WHERE ACTIVE = 1";
		if ($searchQuery !== '')
		{
			$result .= " AND TITLE LIKE ? ";
		}
		$result .= "
				ORDER BY ui.SORT_ORDER
				LIMIT {$offset}, {$amountItems};";
		return $result;
	}


	private function getItemsByPriceQuery(): string
	{
		$result = "SELECT ui.ID as ui_ID,
                        TITLE as TITLE,
                        PRICE as PRICE,
                        SORT_ORDER as SORT_ORDER,
                        SHORT_DESC as SHORT_DESC,
                        ACTIVE as ACTIVE,
                        u.ID IMAGE_ID,
                        u.PATH IMAGE_PATH,
                        u.IS_MAIN IMAGE_IS_MAIN
				FROM up_item ui
				INNER JOIN up_image u on ui.ID = u.ITEM_ID AND u.IS_MAIN = 1
				WHERE ACTIVE = 1 AND PRICE > ? AND PRICE < ?
				ORDER BY ui.SORT_ORDER";
		return $result;
	}

	private function getItemsByOrderIdQuery(int $orderId): string
	{
		return "SELECT * FROM up_item
                INNER JOIN up_order_item on ITEM_ID = ID
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
					   ACTIVE as ACTIVE,ITEM_TYPE_ID as ITEM_TYPE_ID, uit.NAME as TYPE_NAME,
					   uis.SPEC_TYPE_ID as SPEC_TYPE_ID, uis.VALUE as VALUE, ust.NAME as ust_NAME, ust.DISPLAY_ORDER as ust_DISPLAY_ORDER, usc.ID as usc_ID,
						usc.NAME as usc_NAME, usc.DISPLAY_ORDER as usc_DISPLAY_ORDER, u.ID as u_ID, u.IS_MAIN as IS_MAIN, u.PATH as PATH
				FROM up_item ui inner join up_item_type uit on ui.ITEM_TYPE_ID = uit.ID AND ui.ID={$id}
                LEFT JOIN up_item_tag ut on ut.ITEM_ID = ui.ID
                LEFT JOIN up_tag on up_tag.ID=ut.TAG_ID
                INNER JOIN up_item_spec uis on ui.ID = uis.ITEM_ID
                INNER JOIN up_spec_type ust on uis.SPEC_TYPE_ID = ust.ID
                INNER JOIN up_spec_category usc on ust.SPEC_CATEGORY_ID = usc.ID
                INNER JOIN up_image u on ui.ID = u.ITEM_ID
				ORDER BY ust_DISPLAY_ORDER, usc_DISPLAY_ORDER;";
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

		return "INSERT INTO up_item_tag (ITEM_ID, TAG_ID) VALUES {$insert};";
	}

	private function getInsertSpecsQuery(int $id, array $specs): string
	{
		$insert = implode(
			',',
			array_map(function(Specification $s) use ($id) {
				return "({$id},'{$s->getId()}','{$s->getValue()}')";
			}, $specs)
		);

		return "INSERT INTO up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE) VALUES {$insert};";
	}

	private function getInsertImagesQuery(int $id, array $images): string
	{
		$insert = implode(
			',',
			array_map(function(ItemsImage $image) use ($id) {
				return "('{$image->getPath()}',{$id},{$image->isMain()})";
			}, $images)
		);

		return "INSERT INTO up_image(PATH, ITEM_ID, IS_MAIN) VALUES {$insert};";
	}

	private function getInsertItemQuery(ItemDetail $item): string
	{
		$date = date('Y-m-d H:i:s');

		return "INSERT INTO up_item(TITLE, PRICE, SHORT_DESC, FULL_DESC, SORT_ORDER, ACTIVE, DATE_CREATE, DATE_UPDATE, ITEM_TYPE_ID) 
				VALUES ('{$item->getTitle()}', {$item->getPrice()}, '{$item->getShortDescription()}', 
				        '{$item->getFullDescription()}', {$item->getSortOrder()}, {$item->getIsActive()}, 
				        '{$date}', '{$date}', {$item->getItemType()->getId()});";
	}

	private function getUpdateItemQuery(ItemDetail $item): string
	{
		$date = date('Y-m-d H:i:s');

		return "UPDATE up_item
				SET TITLE='{$item->getTitle()}',
					PRICE={$item->getPrice()},
					SHORT_DESC='{$item->getShortDescription()}',
					FULL_DESC='{$item->getFullDescription()}',
					SORT_ORDER={$item->getSortOrder()},
					ACTIVE={$item->getIsActive()},
				    ITEM_TYPE_ID={$item->getItemType()->getId()},
				    DATE_UPDATE='{$date}'
				WHERE ID={$item->getId()};";
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
		$image->setId($row['IMAGE_ID']);
		$image->setPath($row['IMAGE_PATH']);
		$image->setIsMain($row['IMAGE_IS_MAIN']);
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
		$image->setPath($row['PATH']);

		return $image;
	}

	private function getItemsMinMaxPriceQuery() :string
	{
		$query = "SELECT MIN(PRICE) AS MINPRICE,
		MAX(PRICE) AS MAXPRICE
		FROM up_item";
		return $query;
	}



	private function getItemsByFiltersQuery($offset, $amountItems,string $searchQuery,string $price,array $tags,array $specs):string
	{

		$query = "
	SELECT DISTINCT 
	ui.ID as ui_ID,
	ui.TITLE,
	ui.PRICE,
	ui.SHORT_DESC,
	ui.FULL_DESC,
	ui.SORT_ORDER,
	ui.ACTIVE,
	ui.DATE_CREATE,
	ui.DATE_UPDATE,
	ui.ITEM_TYPE_ID,
	u.ID as IMAGE_ID,
    u.PATH as IMAGE_PATH,
    u.IS_MAIN as IMAGE_IS_MAIN
FROM up_item as ui";
		if (!empty($tags))
		{
			$query.= " INNER join (select ITEM_ID,
    TAG_ID
FROM
(select
	upt.ITEM_ID as ITEM_ID,
    upt.TAG_ID as TAG_ID,
    COUNT(TAG_ID) as COUNT
FROM up_item_tag as upt
where ";
			$where = [];
			foreach ($tags as $tag)
			{
				$where[]='TAG_ID = '.$tag;
			}
			$query .= implode(' OR ', $where);
			$query .= "
group by ITEM_ID
) as l
WHERE COUNT = ";
			$query .= count($tags);
			$query .= ") as uig on uig.ITEM_ID = ID";
		}
		if (!empty($specs))
		{
			$query .= "
INNER JOIN (select
	ITEM_ID
FROM
(select
	 ITEM_ID as ITEM_ID,
	 COUNT(VALUE) as COUNT
 FROM up_item_spec
 WHERE ";
			$where = [];
			foreach ($specs as $spec=>$value)
			{
				$spec = explode('=',$value);
				$where[]="(SPEC_TYPE_ID = " . $spec[0]. " AND VALUE = ". "'" . $spec[1] . "')";
			}
			$query .= implode(' OR ', $where);
			$query .= " GROUP BY ITEM_ID) as ls
WHERE COUNT =";
			$query .= count($specs);
			$query .= ") as uis on uis.ITEM_ID = ID";
		}
		if (!($price === ""))

		{
			$query .="
INNER JOIN (select ID as ITEM_ID,
                   PRICE as PRICE
            FROM up_item
            WHERE ";
			$minMaxPrice = explode('-',$price);
			$query .= 'PRICE > '. $minMaxPrice[0] . ' AND PRICE < ' . $minMaxPrice[1];
			$query .= ") as uip on uip.ITEM_ID = ID";
		}
		if (!($searchQuery === ""))
		{
			$query .="
INNER JOIN (select ID as ITEM_ID,
                   TITLE as TITLE
            FROM up_item
            WHERE TITLE LIKE '%";
			$query .= $searchQuery;
			$query .= "%') as uit on uit.ITEM_ID = ID";
		}
		$query .="
		INNER JOIN up_image u on ui.ID = u.ITEM_ID AND u.IS_MAIN = 1
		WHERE ACTIVE = 1 
		ORDER BY ui.SORT_ORDER
		LIMIT {$offset}, {$amountItems}";
		return $query;
	}














































private function getItemsAmountByFiltersQuery(string $searchQuery,string $price,array $tags,array $specs):string
{

	$query = "
	SELECT DISTINCT 
	count(1) as num_items
FROM up_item as ui";
	if (!empty($tags))
	{
		$query.= " INNER join (select ITEM_ID,
    TAG_ID
FROM
(select
	upt.ITEM_ID as ITEM_ID,
    upt.TAG_ID as TAG_ID,
    COUNT(TAG_ID) as COUNT
FROM up_item_tag as upt
where ";
		$where = [];
		foreach ($tags as $tag)
		{
			$where[]='TAG_ID = '.$tag;
		}
		$query .= implode(' OR ', $where);
		$query .= "
group by ITEM_ID
) as l
WHERE COUNT = ";
		$query .= count($tags);
		$query .= ") as uig on uig.ITEM_ID = ID";
	}
	if (!empty($specs))
	{
		$query .= "
INNER JOIN (select
	ITEM_ID
FROM
(select
	 ITEM_ID as ITEM_ID,
	 COUNT(VALUE) as COUNT
 FROM up_item_spec
 WHERE ";
		$where = [];
		foreach ($specs as $spec=>$value)
		{
			$spec = explode('=',$value);
			$where[]="(SPEC_TYPE_ID = " . $spec[0]. " AND VALUE = ". "'" . $spec[1] . "')";
		}
		$query .= implode(' OR ', $where);
		$query .= " GROUP BY ITEM_ID) as ls
WHERE COUNT =";
		$query .= count($specs);
		$query .= ") as uis on uis.ITEM_ID = ID";
	}
	if (!($price === ""))

	{
		$query .="
INNER JOIN (select ID as ITEM_ID,
                   PRICE as PRICE
            FROM up_item
            WHERE ";
		$minMaxPrice = explode('-',$price);
		$query .= 'PRICE > '. $minMaxPrice[0] . ' AND PRICE < ' . $minMaxPrice[1];
		$query .= ") as uip on uip.ITEM_ID = ID";
	}
	if (!($searchQuery === ""))
	{
		$query .="
INNER JOIN (select ID as ITEM_ID,
                   TITLE as TITLE
            FROM up_item
            WHERE TITLE LIKE '%";
		$query .= $searchQuery;
		$query .= "%') as uit on uit.ITEM_ID = ID";
	}
	$query .="
		WHERE ACTIVE = 1";
	return $query;
}


}
