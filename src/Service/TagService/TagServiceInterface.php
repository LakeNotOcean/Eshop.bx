<?php

namespace Up\Service\TagService;

use Up\Entity\EntityArray;


interface TagServiceInterface
{

	public function save(array $tags): EntityArray;

}
