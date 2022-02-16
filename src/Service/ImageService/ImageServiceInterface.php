<?php

namespace Up\Service\ImageService;

use Up\Entity\ItemsImage;


interface ImageServiceInterface
{
	public function addImage(array $imageParams, bool $isMain): ItemsImage;
	public function addImages(array $imagesParams, array $isMains): array;
}
