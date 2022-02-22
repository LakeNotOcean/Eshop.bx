<?php

namespace Up\Entity;

class UserItem extends ItemDetail
{
	protected $isFavorite;

	public function setItem(Item $item): void
	{
		$this->id = $item->id;
		$this->title = $item->title;
		$this->price = $item->price;
		$this->shortDescription = $item->shortDescription;
		$this->sortOrder = $item->sortOrder;
		$this->isActive = $item->isActive;
		$this->mainImage = $item->mainImage;
	}

	public function setItemDetail(ItemDetail $itemDetail): void
	{
		$this->setItem($itemDetail);
		$this->fullDescription = $itemDetail->fullDescription;
		$this->images = $itemDetail->images;
		$this->tags = $itemDetail->tags;
		$this->itemType = $itemDetail->itemType;
		$this->specificationCategoriesList->setEntitiesArray($itemDetail->getSpecificationCategoriesList());
	}

	/**
	 * @return bool
	 */
	public function getIsFavorite(): bool
	{
		return $this->isFavorite;
	}

	/**
	 * @param bool $isFavorite
	 */
	public function setIsFavorite(bool $isFavorite): void
	{
		$this->isFavorite = $isFavorite;
	}

}
