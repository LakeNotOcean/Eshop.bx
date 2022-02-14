<?php

namespace Up\DAO\TagDAO;

use Up\Entity\EntityArray;


interface TagDAOInterface
{
	public function save(array $tags): EntityArray;

	public function getTagsByNames(array $names): EntityArray;
}
