<?php

namespace Up\Core\Entity;

class ItemDetail extends Item
{
	private $fullDescription = '';
	private $tags = [];
	private $specification;

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
	 * @return ItemsSpecification
	 */
	public function getSpecification(): ItemsSpecification
	{
		return $this->specification;
	}

	/**
	 * @param ItemsSpecification $specification
	 */
	public function setSpecification(ItemsSpecification $specification): void
	{
		$this->specification = $specification;
	}
}