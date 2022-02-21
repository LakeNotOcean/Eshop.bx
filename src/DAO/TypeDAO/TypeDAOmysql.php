<?php

namespace Up\DAO\TypeDAO;

use Up\Core\Database\DefaultDatabase;

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
		$query = TypeDAOqueries::getTypeIdBySearchQuery();
		$preparedStatement = $this->DBConnection->prepare($query);
		$preparedStatement->execute(["%$searchQuery%"]);
		$types = [];
		while ($row = $preparedStatement->fetch())
		{
			$types[]=$row["ID"];
		}
		return $types;
	}
}