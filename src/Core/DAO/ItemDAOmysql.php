<?php

namespace Up\Core\DAO;

use Up\Core\DataBase\BaseDatabase;
use Up\Core\Entity\Item;
use Up\Core\Entity\ItemDetail;
use Up\Core\Entity\ItemsImage;
use Up\Core\Entity\ItemsSpecification;
use Up\Core\Entity\ItemsTag;

class ItemDAOmysql implements ItemDAO
{
	private const PAGE_SIZE = 10;
	private $DBConnection;

	public function __construct(BaseDatabase $DBConnection)
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
		$tags = [];
		$imagesId = '';
		while ($row = $result->fetch())
		{
			if($item->getId() === 0) //если еще не установили id, т.е если это первая итерация
			{
				$this->mapItemCommonInfo($item, $row);
				$item->setFullDescription($row['FULL_DESC']);
				$specification = new ItemsSpecification();
				$this->mapItemsSpecificationInfo($specification, $row);
				$item->setSpecification($specification);
				$imagesId = $row['IMAGES_ID'];
			}
			$tag = new ItemsTag();
			$tag->setId($row['TAG_ID']);
			$tag->setName($row['TAG']);
			$tags[] = $tag;
		}
		$item->setTags($tags);
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
					   ACTIVE,
					   ut.ID TAG_ID,
					   ut.TITLE TAG,
                       MANUFACTURER,
					   COUNTRY,
					   WARRANTY,
					   RELEASE_YEAR,
					   MEMORY_SIZE,
					   MEMORY_TYPE,
					   BUS_WIDTH,
					   TECHNICAL_PROCESS,
					   CHIP_FREQ,
					   MEM_FREQ,
					   MAX_RESOLUTION,
					   OUT_CONNECTORS,
					   INTERFACE,
					   ADDITIONAL_POWER,
					   REQUIRED_POWER,
					   FANS_NUM,
					   LENGTH,
					   THICKNESS,
					   (SELECT GROUP_CONCAT(up_image.ID)
					   FROM up_image
					   WHERE up_image.ITEM_ID = 2) IMAGES_ID
				FROM up_item ui
				INNER JOIN up_item_tag ON ui.ID = up_item_tag.ITEM_ID AND ui.ID = 2
				INNER JOIN up_tag ut on up_item_tag.TAG_ID = ut.ID
				INNER JOIN up_specs us on ui.ID = us.ITEM_ID;";
	}

	private function getImagesByIdQuery(string $imagesId): string
	{
		return "SELECT u.ID IMAGE_ID,
                        u.PATH IMAGE_PATH,
                        u.WIDTH IMAGE_WIDTH,
                        u.HEIGHT IMAGE_HEIGHT,
                        u.IS_MAIN IMAGE_IS_MAIN
				FROM up_image u
				WHERE ID in ({$imagesId})";
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
	private function mapItemsSpecificationInfo(ItemsSpecification $specification, array $row)
	{
		$specification->setManufacturer($row['MANUFACTURER']);
		$specification->setCountry($row['COUNTRY']);
		$specification->setWarranty($row['WARRANTY']);
		$specification->setReleaseYear($row['RELEASE_YEAR']);
		$specification->setMemorySize($row['MEMORY_SIZE']);
		$specification->setMemoryType($row['MEMORY_TYPE']);
		$specification->setBusWidth($row['BUS_WIDTH']);
		$specification->setTechnicalProcess($row['TECHNICAL_PROCESS']);
		$specification->setChipFrequency($row['CHIP_FREQ']);
		$specification->setMemoryFrequency($row['MEM_FREQ']);
		$specification->setMaxResolution($row['MAX_RESOLUTION']);
		$specification->setOutConnectors($row['OUT_CONNECTORS']);
		$specification->setInterface($row['INTERFACE']);
		$specification->setAdditionalPower($row['ADDITIONAL_POWER']);
		$specification->setRequiredPower($row['REQUIRED_POWER']);
		$specification->setFansNum($row['FANS_NUM']);
		$specification->setLength($row['LENGTH']);
		$specification->setThickness($row['THICKNESS']);
	}
}