<?php

namespace Up\DAO\ItemDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\EntityArray;
use Up\Entity\Item;
use Up\Entity\ItemDetail;
use Up\Entity\ItemsImage;
use Up\Entity\ItemsTag;
use Up\Entity\ItemType;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;

class ItemDAOmysql implements ItemDAO
{
	private $DBConnection;

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
			if (!$item->getTags()->contains($row['TAG_ID']))
			{
				$item->getTags()->addEntity($this->getTag($row));
			}
			if (!$item->getSpecificationCategoriesList()->contains($row['usc_ID']))
			{
				$item->getSpecificationCategoriesList()->addEntity(
					new SpecificationCategory(
						$row['usc_ID'], $row['usc_NAME'], $row['usc_DISPLAY_ORDER']
					)
				);
			}
			if (
				!$item->getSpecificationCategoriesList()->getEntity($row['usc_ID'])->getSpecificationList()->contains(
						$row['SPEC_TYPE_ID']
					)
			)
			{
				$item->getSpecificationCategoriesList()->getEntity($row['usc_ID'])->getSpecificationList()->addEntity(
						new Specification(
							$row['SPEC_TYPE_ID'], $row['ust_NAME'], $row['ust_DISPLAY_ORDER'], $row['VALUE']
						)
					);
			}
			if (!$item->getImages()->contains($row['u_ID']))
			{
				$item->getImages()->addEntity($this->getItemsImage($row));
				if ($row['IS_MAIN'] and !$item->isSetMainImage())
				{
					$item->setMainImage($item->getImages()->getEntity($row['u_ID']));
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

		$oldTags = $oldItem->getTags()->getEntitiesArray();
		$newTags = $item->getTags()->getEntitiesArray();

		$oldSpecsCat = $oldItem->getSpecificationCategoriesList()->getEntitiesArray();
		$newSpecsCat = $item->getSpecificationCategoriesList()->getEntitiesArray();

		$oldSpecs = $this->getSpecsFromCategory($oldSpecsCat);
		$newSpecs = $this->getSpecsFromCategory($newSpecsCat);

		$oldImages = $oldItem->getImages()->getEntitiesArray();
		$newImages = $item->getImages()->getEntitiesArray();

		if (!empty($oldTags))
		{
			$this->DBConnection->query($this->getDeleteWhereAndWhereInQuery($item->getId(), array_keys($oldTags), [
				'table_name' => 'up_item_tag',
				'item_id_name' => 'ITEM_ID',
				'other_id_name' => 'TAG_ID'])
			);
		}
		if(!empty($oldSpecs))
		{
			$this->DBConnection->query($this->getDeleteWhereAndWhereInQuery($item->getId(), array_keys($oldSpecs), [
				'table_name' => 'up_item_spec',
				'item_id_name' => 'ITEM_ID',
				'other_id_name' => 'SPEC_TYPE_ID'])
			);
		}
		if(!empty($oldImages))
		{
			$this->DBConnection->query($this->getDeleteWhereAndWhereInQuery($item->getId(), array_keys($oldImages),[
				'table_name' => 'up_image',
				'item_id_name' => 'ITEM_ID',
				'other_id_name' => 'ID'])
			);
		}
		if(!empty($newTags))
		{
			$this->DBConnection->query($this->getInsertTagsQuery($item->getId(), $newTags));
		}
		if(!empty($newSpecs))
		{
			$this->DBConnection->query($this->getInsertSpecsQuery($item->getId(), $newSpecs));
		}
		if(!empty($newImages))
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
		$tags = $item->getTags()->getEntitiesArray();

		$specsCat = $item->getSpecificationCategoriesList()->getEntitiesArray();

		$specs = $this->getSpecsFromCategory($specsCat);

		$images = $item->getImages()->getEntitiesArray();

		$this->DBConnection->query($this->getInsertTagsQuery($id, $tags));
		$this->DBConnection->query($this->getInsertSpecsQuery($id, $specs));
		if(!empty($images))
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
			$category = $cat->getSpecificationList()->getEntitiesArray();
			foreach ($category as $id => $spec)
			{
				$specs[$id] = $spec;
			}
		}

		return $specs;
	}

	private function getItemsQuery(int $offset, int $amountItems): string
	{
		return "SELECT ui.ID as ui_ID,
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
				ORDER BY ui.SORT_ORDER
				LIMIT {$offset}, {$amountItems};";
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
		$insert = implode(',', array_map(function(Specification $s) use ($id){
			return "({$id},'{$s->getId()}','{$s->getValue()}')";
		}, $specs));
		return "INSERT INTO up_item_spec (ITEM_ID, SPEC_TYPE_ID, VALUE) VALUES {$insert};";
	}

	private function getInsertImagesQuery(int $id, array $images): string
	{
		$insert = implode(',', array_map(function(ItemsImage $image) use($id){
			return "({$image->getPath()},{$id},{$image->isMain()})";
		}, $images));
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
				SET TITLE={$item->getTitle()},
					PRICE={$item->getPrice()},
					SHORT_DESC={$item->getShortDescription()},
					FULL_DESC={$item->getFullDescription()},
					SORT_ORDER={$item->getSortOrder()},
					ACTIVE={$item->getIsActive()},
				    ITEM_TYPE_ID={$item->getItemType()->getId()},
				    DATE_UPDATE={$date}
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
}