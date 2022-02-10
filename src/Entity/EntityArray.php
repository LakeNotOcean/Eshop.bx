<?php

namespace Up\Entity;

class EntityArray
{

	/**
	 * @var array<int,Entity>
	 */
	protected $entities = [];

	public function getEntity(int $id): Entity
	{
		return $this->entities[$id];
	}
	public function addEntity(Entity $entity): void
	{
		$this->entities[$entity->getId()] = $entity;
	}

	/**
	 * @return array<int,Entity>
	 */
	public function getEntitiesArray(): array
	{
		return $this->entities;
	}

	public function setEntitiesArray(Entity ...$entities): void
	{
		$this->entities = [];
		foreach ($entities as $entity)
		{
			$this->entities[$entity->getId()] = $entity;
		}
	}

	public function contains(int $id): bool
	{
		return isset($this->entities[$id]);
	}
}