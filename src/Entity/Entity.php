<?php

namespace Up\Entity;


abstract class Entity
{
	protected $id = 0;

	public function getId(): int
	{
		return $this->id;
	}

	public function setId(int $id)
	{
		$this->id = $id;
		return $this;
	}
}
