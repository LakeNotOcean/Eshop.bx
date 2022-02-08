<?php

namespace Up\DAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\Item;
use Up\Entity\ItemDetail;
use Up\Entity\ItemsImage;
use Up\Entity\ItemType;

class ItemDAOmysql implements ItemDAO
{
	private const PAGE_SIZE = 10;
	private $DBConnection;

	public function __construct(DefaultDatabase $DBConnection)
	{
		$this->DBConnection = $DBConnection;
	}

	public function getItems(int $page): array
	{
		$from = $page * self::PAGE_SIZE;
		$to = ($page + 1) * self::PAGE_SIZE;
		$result = $this->DBConnection->query($this->getItemsQuery($from, $to));
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

	public function getItemDetailById(int $id): ItemDetail
	{
		$result = $this->DBConnection->query($this->getItemDetailByIdQuery($id));
		$item = new ItemDetail();
		$imagesId = '';
		while ($row = $result->fetch())
		{
			if ($item->getId() === 0) //если еще не установили id, т.е если это первая итерация
			{
				$this->mapItemCommonInfo($item, $row);
				$item->setFullDescription($row['FULL_DESC']);
				$item->setItemType(new ItemType($row['ITEM_TYPE_ID'], $row['TYPE_NAME']));
				$imagesId = $row['IMAGES_ID'];
			}
		}
		if (empty($imagesId))
		{
			$imagesId = '0';
		}
		$result = $this->DBConnection->query($this->getImagesByIdQuery($imagesId));
		$images = [];
		while ($row = $result->fetch())
		{
			$image = new ItemsImage();
			$this->mapItemsImageInfo($image, $row);
			if ($image->isMain())
			{
				$item->setMainImage($image);
			}
			$images[] = $image;
		}
		$item->setImages($images);

		return $item;
	}

	private function getItemsQuery(int $from, int $to): string
	{
		return "SELECT ui.ID,
                        TITLE,
                        PRICE,
                        SORT_ORDER,
                        SHORT_DESC,
                        ACTIVE,
                        u.ID IMAGE_ID,
                        u.PATH IMAGE_PATH,
                        u.WIDTH IMAGE_WIDTH,
                        u.HEIGHT IMAGE_HEIGHT,
                        u.IS_MAIN IMAGE_IS_MAIN
				FROM up_item ui
				INNER JOIN up_image u on ui.ID = u.ITEM_ID AND u.IS_MAIN = 1
				ORDER BY ui.SORT_ORDER
				LIMIT {$from}, {$to};";
	}

	private function getItemDetailByIdQuery(int $id): string
	{
		return "SELECT ui.ID,
					   ui.TITLE,
					   PRICE,
					   SORT_ORDER,
					   SHORT_DESC,
					   FULL_DESC,
					   ACTIVE,ITEM_TYPE_ID, uit.NAME as TYPE_NAME,
					   (SELECT GROUP_CONCAT(up_image.ID)
					   FROM up_image
					   WHERE up_image.ITEM_ID = {$id}) IMAGES_ID
				FROM up_item ui left join up_item_type uit on ui.ITEM_TYPE_ID = uit.ID
				WHERE ui.ID={$id};";
	}

	private function getImagesByIdQuery(string $imagesId): string
	{
		return "SELECT u.ID IMAGE_ID,
                        u.PATH IMAGE_PATH,
                        u.WIDTH IMAGE_WIDTH,
                        u.HEIGHT IMAGE_HEIGHT,
                        u.IS_MAIN IMAGE_IS_MAIN
				FROM up_image u
				WHERE ID in ({$imagesId});";
	}

	private function mapItemCommonInfo(Item $item, array $row)
	{
		$item->setId($row['ID']);
		$item->setTitle($row['TITLE']);
		$item->setPrice($row['PRICE']);
		$item->setShortDescription($row['SHORT_DESC']);
		$item->setSortOrder($row['SORT_ORDER']);
		$item->setIsActive($row['ACTIVE']);
	}

	private function mapItemsImageInfo(ItemsImage $image, array $row)
	{
		$image->setId($row['IMAGE_ID']);
		$image->setPath($row['IMAGE_PATH']);
		$image->setHeight($row['IMAGE_HEIGHT']);
		$image->setWidth($row['IMAGE_WIDTH']);
		$image->setIsMain($row['IMAGE_IS_MAIN']);
	}
}