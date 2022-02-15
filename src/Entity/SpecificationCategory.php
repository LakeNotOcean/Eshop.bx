<?php

namespace Up\Entity;


class SpecificationCategory extends Entity
{
	protected $name;

	/**
	 * @var EntityArray
	 */
	protected $specificationList;
	protected $displayOrder;

	public function __construct(int $id = 1, string $name = '', int $displayOrder = 0)
	{
		$this->id = $id;
		$this->name = $name;
		$this->displayOrder = $displayOrder;
		$this->specificationList = new EntityArray();
	}

	/**
	 * @return array<int,Specification>
	 */
	public function getSpecifications(): array
	{
		return $this->specificationList->getEntitiesArray();
	}

	/**
	 * @param array<int,Specification> $specifications
	 */
	public function setSpecifications(array $specifications): void
	{
		$this->specificationList->setEntitiesArray($specifications);
	}

	public function getSpecificationById(int $id): Specification
	{
		return $this->specificationList->getEntity($id);
	}

	public function setSpecification(Specification $specification): void
	{
		$this->specificationList->addEntity($specification);
	}

	public function hasSpecification(int $id): bool
	{
		return $this->specificationList->contains($id);
	}

	public function getDisplayOrder(): int
	{
		return $this->displayOrder;
	}

	public function setDisplayOrder(string $displayOrder): void
	{
		$this->displayOrder = $displayOrder;
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;

	}
}
