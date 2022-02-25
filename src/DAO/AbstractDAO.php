<?php

namespace Up\DAO;

use PDOStatement;
use Up\Core\Database\BaseDatabase;
use ValueError;


abstract class AbstractDAO
{
	/**
	 * @var BaseDatabase
	 */
	protected $dbConnection;

	protected function getSelectPrepareStatement(string $tableName, array $whereConditions = []): PDOStatement
	{
		$preparedQuery = 'SELECT * FROM ' . $tableName;

		if (!empty($whereConditions))
		{
			$preparedQuery .= ' WHERE ' . $this->getWhereConditions($tableName, $whereConditions);
		}

		return $this->dbConnection->prepare($preparedQuery . ';');
	}

	/**
	 * @param string $tableName
	 * Пример: 'up_image'
	 * @param array<string, string> $whereConditions
	 * Пример: ['IS_MAIN' => '=', 'ID' => '>']
	 *
	 * @return string
	 * Пример: 'up_image.IS_MAIN = :IS_MAIN and up_image.ID > :ID'
	 */
	protected function getWhereConditions(string $tableName, array $whereConditions): string
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

	protected function getInsertPrepareStatement(string $tableName, array $columns, int $rowsCount = 1): PDOStatement
	{
		$this->checkEmptyColumns($columns);
		$preparedQuery = 'insert into ' . $tableName . '(' . implode(', ', $columns) . ')' . ' values ';
		$preparedQuery .= rtrim(str_repeat(
			'(' . implode(', ', array_fill(0, count($columns), '?')) . '),',
			$rowsCount
		), ',');

		return $this->dbConnection->prepare($preparedQuery . ';');
	}

	protected function getUpdatePrepareStatement(string $tableName, array $columns, string $idColumnName): PDOStatement
	{
		$this->checkEmptyColumns($columns);
		$preparedQuery = "UPDATE {$tableName} SET "
			. implode(', ', array_map(static function(string $column){return "{$column}=?";}, $columns))
			. " WHERE {$idColumnName}=?";
		return $this->dbConnection->prepare($preparedQuery . ';');
	}

	protected function getPreparedGroup(int $preparedElementsCount)
	{
		return '(' . implode(', ', array_fill(0, $preparedElementsCount, '?')) . ')';
	}

	private function checkEmptyColumns(array $columns): void
	{
		if (empty($columns))
		{
			$columnsRepresent = var_export($columns);
			throw new ValueError(
				"Parameters columns are empty. Columns: {$columnsRepresent}"
			);
		}
	}
}
