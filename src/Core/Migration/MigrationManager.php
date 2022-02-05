<?php

namespace Up\Core\Migration;

use DirectoryIterator;
use Exception;
use MigrationException;
use PDOException;
use Up\Core\DataBase\DefaultDatabase;
use Up\Core\Settings;

class MigrationManager
{
	private $database;
	private $migrationDir;

	private $createTableScript = 'CREATE TABLE IF NOT EXISTS up_migration (LAST_MIGRATION text NOT NULL)';
	private $removeLastMigrationScript = 'TRUNCATE TABLE up_migration;';
	public const dateFormat = 'Y_m_d_H-i-s';
	private $getLastMigrationScript = 'SELECT * FROM up_migration LIMIT 1;';
	private $minMigrationDate = '1900_01_01_00-00-00_minimum';
	private $migrationDateLen;

	public function __construct(DefaultDatabase $database)
	{
		$this->database = $database;
		$configService = Settings::getInstance();
		$this->migrationDir = $configService->getMigrationDirPath();
		$this->migrationDateLen=strlen(date(self::dateFormat));
	}

	private function executeQuery(string $query, string $errorMessage = ""): void
	{
		try
		{
			$this->database->query($query);
		}
		catch (PDOException $exception)
		{
			$this->triggerError($errorMessage);
		}
	}

	private function writeLastMigrationRecord(string $migrationDate): void
	{
		$this->executeQuery($this->removeLastMigrationScript, 'failed to remove last migration script');
		$addNewMigrationScript = "INSERT INTO up_migration (LAST_MIGRATION) VALUES ('$migrationDate')";
		$this->executeQuery($addNewMigrationScript, 'failed to apply migration ' . $migrationDate);
	}

	private function triggerError(string $errorMessage = ""): void
	{
		trigger_error(
			$errorMessage . ':' . $this->database->errorCode() . ':' . $this->database->errorInfo()[2],
			E_USER_ERROR
		);
	}

	/**
	 * @throws MigrationException
	 */
	private function createMigrationScriptsDir(): void
	{
		$path = $this->migrationDir;
		if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path))
		{
			throw new MigrationException(sprintf('Directory "%s" was not created', $path));
		}
	}

	private function getMigrationFilesList(): array
	{
		$directoryIterator = new DirectoryIterator($this->migrationDir);
		$migrationFilesArray = [];
		foreach ($directoryIterator as $fileInfo)
		{
			$migrationFilesArray[] =clone $fileInfo;
		}
		uasort($migrationFilesArray, function($a,$b) {
			return strncmp($a->getFilename(),$b->getFilename(),$this->migrationDateLen);
		});
		return $migrationFilesArray;
	}

	private function getLastSuccessfulMigrationDate(string $migrationString, int $formatLen): string
	{
		if (empty($migrationString))
		{
			return $this->minMigrationDate;
		}

		return substr($migrationString, 0, $formatLen);
	}

	protected function formatMigrationName(string $migrationName = ""): string
	{
		return date(self::dateFormat) . '_' . $migrationName;
	}

	/**
	 * @throws MigrationException
	 */
	public function updateDatabase(): void
	{
		$this->executeQuery($this->createTableScript, 'fail to create migration table ');

		$resultString = "";
		try
		{
			$result = $this->database->query($this->getLastMigrationScript);
			if ($result)
			{
				$resultString = $result->fetchAll()[0][0];
				$resultString = $resultString ? : "";
			}
		}
		catch (Exception $exception)
		{
			$this->triggerError('fail to get last migration script ');
		}
		$lastSuccessfulMigrationDate = $this->getLastSuccessfulMigrationDate($resultString, $this->migrationDateLen);
		$currentMigrationDate = $lastSuccessfulMigrationDate;
		$databaseMigrationDate = $lastSuccessfulMigrationDate;

		$this->createMigrationScriptsDir();
		$directoryFilesList = $this->getMigrationFilesList();
		foreach ($directoryFilesList as $fileInfo)
		{
			$fileDate = substr($fileInfo->getFilename(), 0, $this->migrationDateLen);

			if ($fileDate === '.' || $fileDate === '..')
			{
				continue;
			}
			if (strncmp($currentMigrationDate, $fileDate, $this->migrationDateLen) < 0)
			{
				$query = file_get_contents($fileInfo->getPathname());
				try
				{
					$result = $this->database->query($query);
					if (!$result && $databaseMigrationDate !== $lastSuccessfulMigrationDate)
					{
						$this->writeLastMigrationRecord($currentMigrationDate);
					}
				}
				catch (Exception $exception)
				{
					if ($databaseMigrationDate !== $lastSuccessfulMigrationDate)
					{

						$this->writeLastMigrationRecord($currentMigrationDate);
					}
					$this->triggerError('failed to apply migration ' . $fileDate);
				}

				$currentMigrationDate = $fileDate;
				$lastSuccessfulMigrationDate = $currentMigrationDate;
			}
		}
		if ($currentMigrationDate !== $databaseMigrationDate && $currentMigrationDate !== $this->minMigrationDate)
		{
			$this->writeLastMigrationRecord($currentMigrationDate);
		}
	}

	/** Перед применением выполняет все предыдущие миграции
	 * @throws MigrationException
	 */
	public function addAndApplyMigration(string $changeDatabaseScript, string $migrationName): void
	{
		if (!Settings::getInstance()->isDev())
		{
			return;
		}
		$this->updateDatabase();

		$this->executeQuery($changeDatabaseScript, 'failed to execute received script ');
		$this->addMigrationRecord($changeDatabaseScript, $migrationName);
	}

	/**
	 * @throws MigrationException
	 */
	public function addMigrationRecord(string $changeDatabaseScript, $migrationName): void
	{
		$this->updateDatabase();

		$this->executeQuery($this->removeLastMigrationScript, 'failed to remove last migration script ');

		$time = $this->formatMigrationName();

		$addNewMigrationScript = "INSERT INTO up_migration (LAST_MIGRATION) VALUES ('$time')";
		$this->executeQuery($addNewMigrationScript, 'failed to add new migration script ' . $migrationName);
		$name = $this->formatMigrationName($migrationName);
		file_put_contents($this->migrationDir . $name . '.sql', $changeDatabaseScript);
	}
}



