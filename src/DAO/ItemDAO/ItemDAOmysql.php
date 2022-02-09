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
		$tags = [];
		$specs = [];
		$images = [];

		while ($row = $result->fetch())
		{
			if ($item->getId() === 0)
			{
				$this->mapDetailItemInfo($item, $row);
			}
			if (!isset($tags[$row['TAG_ID']]))
			{
				$tags[$row['TAG_ID']] = $this->getTag($row);
			}
			if (!isset($specs[$row['usc_ID']]))
			{
				$specs[$row['usc_ID']] = new SpecificationCategory(
					$row['usc_ID'],
					$row['usc_NAME'],
					$row['usc_DISPLAY_ORDER']
				);
			}
			if (!$specs[$row['usc_ID']]->isSpecificationExist($row['SPEC_TYPE_ID']))
			{
				$specs[$row['usc_ID']]->addToSpecificationList(
					new Specification(
						$row['SPEC_TYPE_ID'], $row['ust_NAME'], $row['ust_DISPLAY_ORDER'], $row['VALUE']
					)
				);
			}
			if (!isset($images[$row['u_ID']]))
			{
				$images[$row['u_ID']] = $this->getItemsImage($row);
				if ($row['IS_MAIN'] and !$item->isSetMainImage())
				{
					$item->setMainImage($images[$row['u_ID']]);
				}
			}
		}
		$item->setTags(array_values($tags));
		$item->setSpecificationCategoryList(array_values($specs));
		$item->setImages(array_values($images));
		return $item;
	}

	private function getItemsQuery(int $from, int $to): string
	{
		return "SELECT ui.ID as ui_ID,
                        TITLE as TITLE,
                        PRICE as PRICE,
                        SORT_ORDER as SORT_ORDER,
                        SHORT_DESC as SHORT_DESC,
                        ACTIVE as ACTIVE,
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
                INNER JOIN up_image u on ui.ID = u.ITEM_ID;";
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
		$image->setHeight($row['IMAGE_HEIGHT']);
		$image->setWidth($row['IMAGE_WIDTH']);
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
}