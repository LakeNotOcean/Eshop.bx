<?php

namespace Up\Entity;


class ItemsTag extends Entity
{
	protected $name = '';
	protected $typeId = 0;

	public function __construct(int $id = 0, string $name = '', int $typeId = 0)
	{
		$this->id = $id;
		$this->name = $name;
		$this->typeId = $typeId;
	}

	/**
	 * @return string
	 */
	public function getName(): string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 */
	public function setName(string $name): void
	{
		$this->name = $name;
	}

	/**
	 * @return int
	 */
	public function getTypeId(): int
	{
		return $this->typeId;
	}

	/**
	 * @param int $typeId
	 */
	public function setTypeId(int $typeId): void
	{
		$this->typeId = $typeId;
	}

}
