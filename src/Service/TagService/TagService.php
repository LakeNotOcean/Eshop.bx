<?php

namespace Up\Service\TagService;

use Up\DAO\TagDAO\TagDAOInterface;
use Up\Entity\ItemsTag;


class TagService implements TagServiceInterface
{
	protected $tagDAO;

	/**
	 * @param \Up\DAO\TagDAO\TagDAOmysql $tagDAO
	 */
	public function __construct(TagDAOInterface $tagDAO)
	{
		$this->tagDAO = $tagDAO;
	}

	/**
	 * @param array<string> $tags
	 *
	 * @return array<int,ItemsTag>
	 */
	public function save(array $tags, int $itemType): array
	{
		$tags = array_map(function(string $tag) use ($itemType) {
			return new ItemsTag(0, $tag, $itemType);
		}, $tags);

		return $this->tagDAO->save($tags, $itemType);
	}

	public function getTagsByItemType(int $queryTypeId): array
	{
		$tags = $this->tagDAO->getTagsByItemType($queryTypeId);
		return $tags;
	}
}
