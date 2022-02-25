<?php

namespace Up\DAO\TagDAO;

use Up\Core\Database\DefaultDatabase;
use Up\DAO\AbstractDAO;
use Up\Entity\EntityArray;
use Up\Entity\ItemsTag;


class TagDAOmysql extends AbstractDAO implements TagDAOInterface
{

	/**
	 * @param \Up\Core\Database\DefaultDatabase $dbConnection
	 */
	public function __construct(DefaultDatabase $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	protected function arrayContainsTag(array $tags, ItemsTag $newTag): bool
	{
		foreach ($tags as $tag)
		{
			if ($tag->getName() === $newTag->getName() && $tag->getTypeId() === $newTag->getTypeId())
			{
				return true;
			}
		}
		return false;
	}

	/**
	 * @param array $tags
	 *
	 * @return array<int,ItemsTag>
	 */
	public function save(array $tags, int $itemType): array
	{
		$names = array_map(function(ItemsTag $tag) {
			return $tag->getName();
		}, $tags);
		$addedTags = $this->getTagsByNames($names);

		$toAdd = [];
		foreach ($tags as $tag)
		{
			if (!$this->arrayContainsTag($addedTags, $tag))
			{
				$toAdd[] = $tag;
			}
		}

		if (!empty($toAdd))
		{
			$tagsCount = count($toAdd);
			$prepareStatement = $this->getInsertPrepareStatement(
				'up_tag', ['TITLE', 'ITEM_TYPE_ID'], $tagsCount);
			$tagsAndItemTypes = [];
			foreach ($toAdd as $tag) {
				$tagsAndItemTypes[] = $tag->getName();
				$tagsAndItemTypes[] = $itemType;
			}
			$prepareStatement->execute($tagsAndItemTypes);
		}

		return $this->getTagsByNames($names);
	}

	/**
	 * @param array<string> $names
	 *
	 * @return array<int,ItemsTag>
	 */
	public function getTagsByNames(array $names): array
	{
		$tags = [];
		$result = $this->dbConnection->query($this->getTagsByNamesQuery($names));
		while ($row = $result->fetch())
		{
			$tags[$row['ID']] = new ItemsTag($row['ID'], $row['TITLE'], $row['ITEM_TYPE_ID']);
		}

		return $tags;
	}

	public function getAllTags(): array
	{
		$result = $this->dbConnection->query($this->getAllTagsQuery());
		while ($row = $result->fetch())
		{
			$tags[] = new ItemsTag($row['ID'], $row['TITLE'], $row['ITEM_TYPE_ID']);
		}

		return $tags;
	}

	public function getTagsByItemType(int $queryTypeId): array
	{
		/*if ($queryTypeId === 0)
		{
			return [];
		}*/
		$result = $this->dbConnection->prepare($this->getTagsByItemTypeQuery($queryTypeId));
		$result->execute();
		$tags = [];
		while ($row = $result->fetch())
		{
			$tags[] = new ItemsTag($row['ID'], $row['TITLE']);
		}
		return $tags;
	}

	private function getAllTagsQuery():string
	{
		return "SELECT * FROM up_tag";
	}

	private function getTagsByNamesQuery(array $names): string
	{
		$names = array_map(function(string $name) {
			return "'{$name}'";
		}, $names);
		$in = implode(',', $names);

		return "SELECT ID, TITLE, ITEM_TYPE_ID FROM up_tag WHERE TITLE IN ({$in})";
	}

	private function getTagsByItemTypeQuery(int $typeId): string
	{
		$query = "SELECT ID, TITLE, ITEM_TYPE_ID FROM up_tag WHERE ITEM_TYPE_ID = " . $typeId;
		return $query;
	}

}
