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
		$addedTags = $this->getTagsByNamesAndItemType($names, $itemType);

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

		return $this->getTagsByNamesAndItemType($names, $itemType);
	}

	/**
	 * @param array<string> $names
	 *
	 * @return array<int,ItemsTag>
	 */
	public function getTagsByNamesAndItemType(array $names, int $itemType): array
	{
		$tags = [];
		$result = $this->dbConnection->prepare($this->getTagsByNamesAndItemTypeQuery(count($names)));
		$preparedArray = $names;
		$preparedArray[] = $itemType;
		$result->execute($preparedArray);
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
		$result = $this->dbConnection->query($this->getTagsByItemTypeQuery($queryTypeId));
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

	private function getTagsByNamesAndItemTypeQuery(int $count): string
	{
		$names = array_fill(0, $count, '?');
		$inNames = implode(',', $names);

		return "SELECT ID, TITLE, ITEM_TYPE_ID FROM up_tag WHERE TITLE IN ({$inNames}) AND ITEM_TYPE_ID=?";
	}

	private function getTagsByItemTypeQuery(int $typeId): string
	{
		$query = "SELECT ID, TITLE, ITEM_TYPE_ID FROM up_tag WHERE ITEM_TYPE_ID = " . $typeId;
		return $query;
	}

}
