<?php

namespace Up\Service\ImageService;

use Up\Entity\ItemsImage;

interface ImageServiceInterface
{
	public function addImage(array $imageParams, bool $isMain): ItemsImage;
}