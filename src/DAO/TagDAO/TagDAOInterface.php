<?php

namespace Up\DAO\TagDAO;

use Up\Entity\EntityArray;
use Up\Entity\ItemsTag;

interface TagDAOInterface
{
	/**
	 * @param array<int,ItemsTag> $tags
	 *
	 * @return array<int,ItemsTag>
	 */
	public function save(array $tags, int $itemType): array;

	/**
	 * @param array<string> $names
	 *
	 * @return array<int,ItemsTag>
	 */
	public function getTagsByNames(array $names): array;

	public function getAllTags(): array;

	public function getTagsByItemType(int $queryTypeId): array;
}
