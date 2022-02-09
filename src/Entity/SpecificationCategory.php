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

	public function __construct(int $id=1, string $name='',int $displayOrder=0)
	{
		$this->id = $id;
		$this->name = $name;
		$this->displayOrder = $displayOrder;
		$this->specificationList = new EntityArray();
	}

	public function addToSpecificationList(Specification $specification): void
	{
		$this->specificationList->addEntity($specification);
	}

	public function getDisplayOrder(): int
	{
		return $this->displayOrder;
	}

	public function setDisplayOrder(string $displayOrder): void
	{
		$this->displayOrder = $displayOrder;
	}

	public function getSpecificationList(): EntityArray
	{
		return $this->specificationList;
	}

	public function setSpecificationList(EntityArray $entityArray)
	{
		$this->specificationList = $entityArray;
	}
	// public function specificationsSort():void
	// {
	// 	usort($this->specificationList,function($a,$b){
	// 		return $a->getDisplayOrder()<=>$b->getDisplayOrder();
	// 	});
	// }

	public function getName(): string
	{
		return $this->name;
	}

	public function setName(string $name): void
	{
		$this->name = $name;

	}
}