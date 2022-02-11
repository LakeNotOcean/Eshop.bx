<?php

namespace Up\Service\TagService;

use Up\Entity\EntityArray;

interface TagService
{

	public function save(array $tags): EntityArray;

}