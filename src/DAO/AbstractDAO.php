<?php

namespace Up\DAO;

use Up\Core\Database\BaseDatabase;
use ValueError;


abstract class AbstractDAO
{
	/**
	 * @var BaseDatabase
	 */
	protected $dbConnection;

	/**
	 * @param string $tableName
	 * Пример: 'up_image'
	 * @param array<string, string> $whereConditions
	 * Пример: ['IS_MAIN' => '=', 'ID' => '>']
	 *
	 * @return string
	 * Пример: 'up_image.IS_MAIN = :IS_MAIN and up_image.ID > :ID'
	 */
	protected function getWhereAndWherePreparedCondition(string $tableName, array $whereConditions): string
	{
		return implode(
			' and ',
			array_map(
				static function($columnName, $comparisonOperation) use ($tableName) {
					return $tableName . '.' . $columnName . ' ' . $comparisonOperation . ' :' . $columnName;
				},
				array_keys($whereConditions),
				$whereConditions
			)
		);
	}

	protected function getInsertValuesPrepareQuery(string $tableName, array $columns, int $rowsCount)
	{
		if (empty($columns))
		{
			$columnsRepresent = var_export($columns);
			throw new ValueError(
				"Parameters columns are empty. Columns: {$columnsRepresent}"
			);
		}

		$preparedQuery = 'insert into ' . $tableName . '(' . implode(', ', $columns) . ')' . ' values ';
		$preparedQuery .= str_repeat(
			'(' . implode(', ', array_fill(0, $rowsCount, '?')) . ')',
			$rowsCount
		);

		return $this->dbConnection->prepare($preparedQuery);
	}
}
