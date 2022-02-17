<?php

namespace Up\Service\ImageService;

use Up\Entity\ItemsImage;


interface ImageServiceInterface
{
	public function addImage(array $imageParams): ItemsImage;
	public function addImages(array $imagesParams, int $itemId): array;
	public function deleteImageById(int $imageId): void;
}
