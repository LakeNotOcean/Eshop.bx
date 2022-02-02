<?php

namespace Up\Core\Migration;

use mysqli;
use Up\Core\Settings;

class MigrationManager
{
	private $database;
	private $migrationDir;

	private $createTableScript = 'CREATE TABLE IF NOT EXISTS migration (
    migration_name text NOT NULL)';
	private $removeLastMigrationScript = 'TRUNCATE TABLE migration';
	const dateFormat = 'Y_m_d_H-i-s';
	private $getLastMigrationScript = 'SELECT * FROM migration LIMIT 1';
	private $minMigrationDate = '1900_01_01_00-00-00_minimum';

	public function __construct(mysqli $database)
	{
		$this->database = $database;
		$configService = Settings::getInstance();
		$this->migrationDir = $configService->getMigrationDirPath();
	}

	private function executeQuery(string $query,string $errorMessage="")
	{
		$result = mysqli_query($this->database, $query);
		if (!$result)
		{
			$this->triggerError($errorMessage);
		}
	}

	private function writeLastMigrationRecord(string $migrationDate)
	{
		$addNewMigrationScript = "INSERT INTO migration (migration_name) VALUES ('$migrationDate')";
		$this->executeQuery($addNewMigrationScript,'faied to apply migration '.$migrationDate);
	}
	private function triggerError(string $errorMessage="")
	{
		trigger_error($errorMessage.$this->database->error, E_USER_ERROR);
	}
	private function createMigrationScriptsDir()
	{
		$path=$_SERVER['DOCUMENT_ROOT'] . $this->migrationDir;
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
	}

	protected function formatMigrationName(string $migrationName = ""): string
	{
		return date(self::dateFormat) . '_' . $migrationName;
	}

	public function updateDatabase()
	{
		$len = strlen(date(self::dateFormat));

		if (!Settings::getInstance()->isDev())
		{
			return;
		}
		$this->executeQuery($this->createTableScript,'fail to create migration table ');
		$result = mysqli_query($this->database, $this->getLastMigrationScript);

		if (!$result)
		{
				$this->triggerError('fail to get last migration script ');
		}

		$resultString = mysqli_fetch_all($result, MYSQLI_ASSOC)[0]['migration_name'];
		if (empty($resultString))
		{
			$lastSuccessfulMigrationDate = $this->minMigrationDate;
		}
		else
		{
			$lastSuccessfulMigrationDate = substr($resultString, 0, $len);

		}
		$currentMigrationDate = $lastSuccessfulMigrationDate;
		$databaseMigrationDate = $lastSuccessfulMigrationDate;

		$this->createMigrationScriptsDir();
		$directoryIterator = new \RecursiveDirectoryIterator($_SERVER['DOCUMENT_ROOT'] . $this->migrationDir);
		foreach ($directoryIterator as $fileInfo)
		{
			$fileDate = substr($fileInfo->getFilename(), 0, $len);

			if ($fileDate === '.' || $fileDate === '..')
			{
				continue;
			}
			if (strncmp($currentMigrationDate, $fileDate, $len) < 0)
			{
				$query = file_get_contents($fileInfo->getPathname());
				$result = mysqli_query($this->database, $query);

				if (!$result)
				{
					if ($databaseMigrationDate !== $lastSuccessfulMigrationDate)
					{
						$this->writeLastMigrationRecord($currentMigrationDate);
					}
					$this->triggerError('failed to apply migration '.$fileDate);
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

	/** Перед применением выполняет все предыдущие миграции*/
	public function addAndApplyMigration(string $changeDatabaseScript, string $migrationName)
	{
		if (!Settings::getInstance()->isDev())
		{
			return;
		}
		$this->updateDatabase();

		$this->executeQuery($changeDatabaseScript,'failed to execute received script ');
		$this->addMigrationRecord($changeDatabaseScript, $migrationName);
	}

	public function addMigrationRecord(string $changeDatabaseScript, $migrationName)
	{
		$this->updateDatabase();

		$this->executeQuery($this->removeLastMigrationScript,'failed to remove last migration script ');

		$time = $this->formatMigrationName();

		$addNewMigrationScript = "INSERT INTO migration (migration_name) VALUES ('$time')";
		$this->executeQuery($addNewMigrationScript,'failed to add new migration script '.$migrationName);
		$name = $this->formatMigrationName($migrationName);
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . $this->migrationDir . $name . '.sql', $changeDatabaseScript);

	}
}



