<?php

namespace Up\Service;

use Up\Core\Entity\ItemDetail;

interface CatalogService
{
	public function getItems(): array;
	public function getItemById(int $id): ItemDetail;
	public function getResultCount(): int;
}