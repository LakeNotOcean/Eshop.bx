<?php

namespace Up\Service\TagService;

use Up\DAO\TagDAO\TagDAOInterface;
use Up\Entity\EntityArray;
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

	public function save(array $tags): EntityArray
	{
		$tags = array_map(static function(string $tag) {
			return new ItemsTag(0, $tag);
		}, $tags);

		return $this->tagDAO->save($tags);
	}
}
