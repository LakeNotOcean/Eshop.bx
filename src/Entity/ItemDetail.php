<?php

namespace Up\Entity;

class ItemDetail extends Item
{
	private $fullDescription = '';
	private $images = [];
	private $tags = [];
	protected $specificationCategoriesList=[];
	protected $itemType;


	public function __construct()
	{
		$this->itemType=new ItemType();
	}

	public function getItemType():ItemType
	{
		return $this->itemType;
	}


	public function setItemType(ItemType $itemType): void
	{
		$this->itemType = $itemType;
	}


	public function getSpecificationCategoriesList(): array
	{
		return $this->specificationCategoriesList;
	}


	public function setSpecificationCategoryList(array $specificationCategoriesList): void
	{
		$this->specificationCategoriesList = $specificationCategoriesList;
	}

	/**
	 * @return array
	 */
	public function getTags(): array
	{
		return $this->tags;
	}

	/**
	 * @param array $tags
	 */
	public function setTags(array $tags): void
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
	 * @return array
	 */
	public function getImages(): array
	{
		return $this->images;
	}

	/**
	 * @param array $images
	 */
	public function setImages(array $images): void
	{
		$this->images = $images;
	}
}