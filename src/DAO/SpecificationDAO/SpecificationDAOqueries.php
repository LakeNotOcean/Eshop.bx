<?php

namespace Up\DAO\SpecificationDAO;

class SpecificationDAOqueries
{
	public static function getCategoriesByItemTypeIdQuery(int $itemTypeId): string
	{
		return "SELECT 
			usc.ID as CAT_ID,
            usc.NAME as CAT_NAME,
            usc.DISPLAY_ORDER as CAT_ORDER,
            u.ID as SPEC_ID,
            u.NAME as SPEC_NAME,
            u.DISPLAY_ORDER as SPEC_ORDER
		FROM up_spec_template ust 
		INNER JOIN up_spec_type u on ust.SPEC_TYPE_ID = u.ID
		INNER JOIN up_spec_category usc on u.SPEC_CATEGORY_ID = usc.ID
		WHERE ITEM_TYPE_ID={$itemTypeId}
		";
	}
	public static function getCategoriesByItemIdQuery(int $itemId): string
	{
		return "SELECT
			usc.ID as CAT_ID,
            usc.NAME as CAT_NAME,
            usc.DISPLAY_ORDER as CAT_ORDER,
            ust.ID as SPEC_ID,
            ust.NAME as SPEC_NAME,
            ust.DISPLAY_ORDER as SPEC_ORDER,
			uis.VALUE as SPEC_VALUE
		FROM up_item_spec uis
		INNER JOIN up_spec_type ust on uis.SPEC_TYPE_ID = ust.ID
		INNER JOIN up_spec_category usc on ust.SPEC_CATEGORY_ID = usc.ID
		WHERE uis.ITEM_ID={$itemId}";
	}

	public static function getTypesQuery():string
	{
		return "SELECT 
       upit.ID as TYPE_ID,
       upit.NAME as TYPE_NAME
		FROM up_item_type upit;";
	}
	public static function getCategoriesByTypesIdQuery():string
	{
		return "SELECT
			usc.ID as CAT_ID,
            usc.NAME as CAT_NAME,
            usc.DISPLAY_ORDER as CAT_ORDER,
            u.ID as SPEC_ID,
            u.NAME as SPEC_NAME,
            u.DISPLAY_ORDER as SPEC_ORDER,
            ust.ITEM_TYPE_ID as TYPE_ID
		FROM up_spec_template ust
		INNER JOIN up_spec_type u on ust.SPEC_TYPE_ID = u.ID
		INNER JOIN up_spec_category usc on u.SPEC_CATEGORY_ID = usc.ID;";
	}
	public static function getCategories():string
	{
		return "SELECT
			usc.ID as CAT_ID,
            usc.NAME as CAT_NAME,
            usc.DISPLAY_ORDER as CAT_ORDER,
            ust.ID as SPEC_ID,
            ust.NAME as SPEC_NAME,
            ust.DISPLAY_ORDER as SPEC_ORDER
		FROM up_spec_type ust 
		INNER JOIN up_spec_category usc on ust.SPEC_CATEGORY_ID = usc.ID;";
	}
}