<?php

namespace Up\Service;

use Up\Entity\ItemDetail;

interface CatalogService
{
	public function getItems(): array;

	public function getItemById(int $id): ItemDetail;

	public function getResultCount(): int;
}