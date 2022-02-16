<?php

namespace Up\Entity;
/**
 *
 * При использовании EntityArray внутри другого Entity рекомендуется скрыть это от клиентского кода,
 * определив в вашем клссе методы
 * ::getYourEntities(): 						array<int,YourEntity>
 * ::setYourEntities(array<int,YourEntity>): 	void
 * ::getYourEntityById(id): 					YourEntity
 * ::setYourEntity(YourEntity): 				void
 * ::hasYourEntity(id): 						bool
 *
 * Желательно, чтобы ваш класс ни принимал EntityArray, ни отдавал EntityArray
 *
 */

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

	/**
	 * @param array<int,Entity> $entities
	 */
	public function setEntitiesArray(array $entities): void
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
