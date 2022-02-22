<?php

namespace Up\Entity;


class ItemDetail extends Item
{
	protected $fullDescription = '';

	/**
	 * @var EntityArray
	 */
	protected $images;

	/**
	 * @var EntityArray
	 */
	protected $tags;

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

	/**
	 * @return array<int,SpecificationCategory>
	 */
	public function getSpecificationCategoriesList(): array
	{
		return $this->specificationCategoriesList->getEntitiesArray();
	}

	/**
	 * @param array<int,SpecificationCategory> $specificationCategoriesList
	 */
	public function setSpecificationCategoryList(array $specificationCategoriesList): void
	{
		$this->specificationCategoriesList->setEntitiesArray($specificationCategoriesList);
	}

	public function setSpecificationCategory(SpecificationCategory $specificationCategory): void
	{
		$this->specificationCategoriesList->addEntity($specificationCategory);
	}

	/**
	 * @param int $id
	 *
	 * @return SpecificationCategory
	 */
	public function getSpecificationCategoryById(int $id): SpecificationCategory
	{
		return $this->specificationCategoriesList->getEntity($id);
	}

	public function hasSpecificationCategory(int $id): bool
	{
		return $this->specificationCategoriesList->contains($id);
	}

	/**
	 * @return array<int,ItemsTag>
	 */
	public function getTags(): array
	{
		return $this->tags->getEntitiesArray();
	}

	/**
	 * @param array<int,ItemsTag> $tags
	 */
	public function setTags(array $tags): void
	{
		$this->tags->setEntitiesArray($tags);
	}

	public function setTag(ItemsTag $tag): void
	{
		$this->tags->addEntity($tag);
	}

	public function getTagById(int $id): void
	{
		$this->tags->getEntity($id);
	}

	public function hasTag(int $id): bool
	{
		return $this->tags->contains($id);
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
	 * @return array<int,ItemsImage>
	 */
	public function getImages(): array
	{
		return $this->images->getEntitiesArray();
	}

	/**
	 * @param array<int,ItemsImage> $images
	 */
	public function setImages(array $images): void
	{
		$this->images->setEntitiesArray($images);
	}

	public function getImageById(int $id): ItemsImage
	{
		return $this->images->getEntity($id);
	}

	public function setImage(ItemsImage $image): void
	{
		$this->images->addEntity($image);
	}

	public function hasImage(int $id): bool
	{
		return $this->images->contains($id);
	}

}
