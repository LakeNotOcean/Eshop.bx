<?php

namespace Up\Core\DAO;

Interface ItemDAO
{
	public function getItems(int $page): array;
}