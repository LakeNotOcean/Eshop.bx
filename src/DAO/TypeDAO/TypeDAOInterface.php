<?php

namespace Up\DAO\TypeDAO;

interface TypeDAOInterface
{
	public function getTypeIdByQuery(string $searchQuery):array;
}