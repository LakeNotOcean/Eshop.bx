<?php

namespace Up\Entity;

class SpecificationCategory
{
	protected $id;
	protected $name;
	protected $specificationList;
	protected $displayOrder;

	public function __construct(int $id = 1, string $name = '', int $displayOrder = 0, array $specificationList = [])
	{
		$this->id = $id;
		$this->name = $name;
		$this->specificationList = $specificationList;
		$this->displayOrder = $displayOrder;
	}

	public function getDisplayOrder(): int
	{
		return $this->displayOrder;
	}

	public function setDisplayOrder(string $displayOrder): void
	{
		$this->displayOrder = $displayOrder;
	}

	public function getSpecificationList(): array
	{
		return $this->specificationList;
	}

	public function addToSpecificationList(Specification $specification): void
	{
		$this->specificationList[$specification->getId()] = $specification;
	}

	public function getSpecificationById(int $specificationId): Specification
	{
		return $this->specificationList[$specificationId];
	}

	public function setSpecificationValueById(int $specificationId, string $value): void
	{
		$this->specificationList[$specificationId]->setValue($value);
	}

	public function isSpecificationExist(int $specificationId): bool
	{
		return isset($this->specificationList[$specificationId]);
	}

	public function specificationsSort(): void
	{
		usort($this->specificationList, function($a, $b) {
			return $a->getDisplayOrder() <=> $b->getDisplayOrder();
		});
	}

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;

	}

	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $id): void
	{
		$this->id = $id;
	}
}