<?php

namespace Up\DAO\TypeDAO;

interface TypeDAOInterface
{
	public function getTypeIdByQuery(string $searchQuery):array;

	public function getTypes(int $offset, int $amountItems):array;

	public function getTypesAmount(): int;


}