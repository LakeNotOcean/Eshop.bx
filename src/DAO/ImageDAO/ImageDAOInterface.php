<?php

namespace Up\DAO\ImageDAO;

use Up\Entity\ItemsImage;

interface ImageDAOInterface
{
	public function save(ItemsImage $image, int $itemId);
}