<?php

namespace Up\DAO\TypeDAO;

class TypeDAOqueries
{
	public static function getTypeIdBySearchQuery():string
	{
		$query = "SELECT DISTINCT ITEM_TYPE_ID AS ID FROM up_item
WHERE TITLE LIKE ?";
		return $query;
	}
}