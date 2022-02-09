<?php

namespace Up\Service\CatalogService;

use Up\Entity\ItemDetail;

interface CatalogServiceInterface
{
	public function getItems(): array;

	public function getItemById(int $id): ItemDetail;

	public function getResultCount(): int;
}