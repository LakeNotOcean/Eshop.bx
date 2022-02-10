<?php

namespace Up\Service\TagService;

use Up\DAO\TagDAO\TagDAO;
use Up\Entity\EntityArray;
use Up\Entity\ItemsTag;

class TagServiceImpl implements TagService
{
	protected $tagDAO;

	public function __construct(TagDAO $tagDAO)
	{
		$this->tagDAO = $tagDAO;
	}

	public function save(array $tags): EntityArray
	{
		$tags = array_map(function(string $tag) {return new ItemsTag(0, $tag);}, $tags);
		return $this->tagDAO->save($tags);
	}
}