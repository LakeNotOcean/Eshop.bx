<?php

namespace Up\DAO\TagDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\EntityArray;
use Up\Entity\ItemsTag;


class TagDAOmysql implements TagDAOInterface
{
	private $DBConnection;

	/**
	 * @param \Up\Core\Database\DefaultDatabase $DBConnection
	 */
	public function __construct(DefaultDatabase $DBConnection)
	{
		$this->DBConnection = $DBConnection;
	}

	/**
	 * @param array $tags
	 *
	 * @return array<int,ItemsTag>
	 */
	public function save(array $tags): array
	{
		$names = array_map(function(ItemsTag $tag) {
			return $tag->getName();
		}, $tags);
		$addedTags = $this->getTagsByNames($names);
		$toAdd = array_diff(
			$names,
			array_map(function(ItemsTag $tag) {
				return $tag->getName();
			}, $addedTags)
		);
		if (!empty($toAdd))
		{
			$result = $this->DBConnection->query($this->getInsertQuery($toAdd));
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
		$result = $this->DBConnection->prepare($this->getTagsByNamesQuery($names));
		$result->execute();
		while ($row = $result->fetch())
		{
			$tags[$row['ID']] = new ItemsTag($row['ID'], $row['TITLE']);
		}

		return $tags;
	}

	public function getAllTags(): array
	{;
		$result = $this->DBConnection->prepare($this->getAllTagsQuery());
		$result->execute();
		while ($row = $result->fetch())
		{
			$tags[] = new ItemsTag($row['ID'], $row['TITLE']);
		}

		return $tags;
	}

	public function getTagsByItemType(int $queryTypeId): array
	{
		/*if ($queryTypeId === 0)
		{
			return [];
		}*/
		$result = $this->DBConnection->prepare($this->getTagsByItemTypeQuery($queryTypeId));
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

	private function getInsertQuery(array $names)
	{
		$names = array_map(function($name) {
			return "('{$name}')";
		}, $names);
		$in = implode(',', $names);

		return "INSERT INTO up_tag (TITLE) VALUES {$in};";
	}

	private function getTagsByNamesQuery(array $names): string
	{
		$names = array_map(function(string $name) {
			return "'{$name}'";
		}, $names);
		$in = implode(',', $names);

		return "SELECT ID, TITLE FROM up_tag WHERE TITLE IN ({$in})";
	}

	private function getTagsByItemTypeQuery(int $typeId): string
	{
		$query = "SELECT ID, TITLE FROM up_tag WHERE ITEM_TYPE_ID = " . $typeId;
		return $query;
	}

}
