<?php

namespace Up\Entity;

class ItemDetail extends Item
{
	private $fullDescription = '';

	/**
	 * @var EntityArray
	 */
	private $images;

	/**
	 * @var EntityArray
	 */
	private $tags;

	/**
	 * @var EntityArray
	 */
	protected $specificationCategoriesList;

	/**
	 * @var ItemType
	 */
	protected $itemType;

	public function __construct()
	{
		$this->itemType = new ItemType();
		$this->images = new EntityArray();
		$this->tags = new EntityArray();
		$this->specificationCategoriesList = new EntityArray();
	}

	public function getItemType(): ItemType
	{
		return $this->itemType;
	}

	public function setItemType(ItemType $itemType): void
	{
		$this->itemType = $itemType;
	}

	public function getSpecificationCategoriesList(): EntityArray
	{
		return $this->specificationCategoriesList;
	}

	public function setSpecificationCategoryList(EntityArray $specificationCategoriesList): void
	{
		$this->specificationCategoriesList = $specificationCategoriesList;
	}

	/**
	 * @return EntityArray
	 */
	public function getTags(): EntityArray
	{
		return $this->tags;
	}

	/**
	 * @param EntityArray $tags
	 */
	public function setTags(EntityArray $tags): void
	{
		$this->tags = $tags;
	}

	/**
	 * @return string
	 */
	public function getFullDescription(): string
	{
		return $this->fullDescription;
	}

	/**
	 * @param string $fullDescription
	 */
	public function setFullDescription(string $fullDescription): void
	{
		$this->fullDescription = $fullDescription;
	}

	/**
	 * @return EntityArray
	 */
	public function getImages(): EntityArray
	{
		return $this->images;
	}

	/**
	 * @param EntityArray $images
	 */
	public function setImages(EntityArray $images): void
	{
		$this->images = $images;
	}
}