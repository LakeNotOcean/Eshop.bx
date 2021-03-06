<?php

namespace Up\Service\ImageService;

use Up\Entity\ItemDetail;
use Up\Entity\ItemsImage;


interface ImageServiceInterface
{
	public function addImage(array $imageParams, ItemDetail $item): ItemsImage;

	public function addImages(array $imagesParams, ItemDetail $item): array;

	public function deleteImageById(int $imageId): void;

	public function deleteImagesByItemId(int $itemId): void;

	public function createImageWithExtension(string $imagePath, string $anotherExtension, string $resultFileMime): string;
}
