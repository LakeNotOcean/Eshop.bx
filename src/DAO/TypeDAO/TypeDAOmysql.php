<?php

namespace Up\DAO\TypeDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\ItemType;

class TypeDAOmysql implements TypeDAOInterface
{
	private $DBConnection;

	/**
	 * @param \Up\Core\Database\DefaultDatabase $DBConnection
	 */
	public function __construct(DefaultDatabase $DBConnection)
	{
		$this->DBConnection = $DBConnection;
	}


	public function getTypeIdByQuery(string $searchQuery):array
	{
		if ($searchQuery === '')
		{
			return [];
		}
		$query = $this->getTypeIdBySearchQuery();
		$preparedStatement = $this->DBConnection->prepare($query);
		$preparedStatement->execute(["%$searchQuery%"]);
		$types = [];
		while ($row = $preparedStatement->fetch())
		{
			$types[]=$row["ID"];
		}
		if (empty($types))
		{
			$types = [0];
		}
		return $types;
	}


	public function getTypes(int $offset, int $amountItems):array
	{
		$query = $this->getTypesQuery($offset,$amountItems);
		$result = $this->DBConnection->query($query);
		$types = [];
		while ($row = $result->fetch())
		{
			$types[] = new ItemType($row["ID"],$row["NAME"]);
		}
		return $types;
	}

	public function getTypesAmount(): int
	{
		$query = $this->getTypesAmountQuery();
		$result = $this->DBConnection->query($query);
		$row = $result->fetch();
		return $row['TYPE_AMOUNT'];
	}

	private function getTypesQuery(int $offset, int $amountItems): string
	{
		$query = "SELECT ID, NAME FROM up_item_type
		LIMIT {$offset}, {$amountItems} ";
		return $query;
	}


	private function getTypeIdBySearchQuery(): string
	{
		$query = "SELECT DISTINCT ITEM_TYPE_ID AS ID FROM up_item
				WHERE TITLE LIKE ?";
		return $query;
	}

	private	function getTypesAmountQuery(): string
	{
		$query = "SELECT count(*) as TYPE_AMOUNT FROM up_item_type";
		return $query;
	}
}