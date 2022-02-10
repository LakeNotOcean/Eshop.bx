<?php

namespace Up\DAO\TagDAO;

use Up\Entity\EntityArray;

interface TagDAO
{
	public function save(array $tags): EntityArray;
	public function getTagsByNames(array $names): EntityArray;
}