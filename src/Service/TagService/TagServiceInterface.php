<?php

namespace Up\Service\TagService;

use Up\Entity\ItemsTag;

interface TagServiceInterface
{

	/**
	 * @param array<string> $tags
	 *
	 * @return array<int,ItemsTag>
	 */
	public function save(array $tags, int $itemType): array;

	public function getTagsByItemType(int $queryTypeId): array;

}
