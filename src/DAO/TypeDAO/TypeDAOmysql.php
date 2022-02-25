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


	public function getTypes():array
	{
		$query = "SELECT * FROM up_item_type";
		$result = $this->DBConnection->query($query);
		$types = [];
		while ($row = $result->fetch())
		{
			$types[] = new ItemType($row["ID"],$row["NAME"]);
		}
		return $types;
	}




	private function getTypeIdBySearchQuery():string
	{
		$query = "SELECT DISTINCT ITEM_TYPE_ID AS ID FROM up_item
				WHERE TITLE LIKE ?";
		return $query;
	}
}