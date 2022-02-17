<?php

namespace Up\DAO\ImageDAO;

use Up\Entity\ItemsImage;

interface ImageDAOInterface
{
	public function save(ItemsImage $image, int $itemId);


	/**
	 * @param array<ItemsImage> $images
	 * @param int $itemId
	 *
	 * @return array<ItemsImage>
	 */
	public function saveAll(array $images, int $itemId): array;

	public function deleteById(int $imageId): void;
}