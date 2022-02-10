<?php

namespace Up\DAO\TagDAO;

use Up\Core\Database\DefaultDatabase;
use Up\Entity\EntityArray;

class TagDAOmysql
{
	private $DBConnection;

	public function __construct(DefaultDatabase $DBConnection)
	{
		$this->DBConnection = $DBConnection;
	}

	public function getTagsByNames(string $names): EntityArray
	{
		return new EntityArray();
	}
}