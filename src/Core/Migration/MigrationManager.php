<?php

namespace Up\Core\Migration;

use mysql_xdevapi\Exception;
use Up\Core\DataBase\DefaultDatabase;
use Up\Core\Settings;

class MigrationManager
{
	private $database;
	private $migrationDir;

	private $createTableScript = 'CREATE TABLE IF NOT EXISTS up_migration (LAST_MIGRATION text NOT NULL)';
	private $removeLastMigrationScript = 'TRUNCATE TABLE migration;';
	const dateFormat = 'Y_m_d_H-i-s';
	private $getLastMigrationScript = 'SELECT * FROM up_migration LIMIT 1;';
	private $minMigrationDate = '1900_01_01_00-00-00_minimum';

	public function __construct(DefaultDatabase $database)
	{
		$this->database = $database;
		$configService = Settings::getInstance();
		$this->migrationDir = $configService->getMigrationDirPath();
	}

	private function executeQuery(string $query, string $errorMessage = "")
	{
		try
		{
			$result = $this->database->MultiQuery($query);
			if (!$result)
			{
				$this->triggerError($errorMessage);
			}
		}
		catch (Exception $exception)
		{
			$this->triggerError($errorMessage);
		}
	}

	private function writeLastMigrationRecord(string $migrationDate)
	{
		$addNewMigrationScript = "INSERT INTO up_migration (LAST_MIGRATION) VALUES ('$migrationDate')";
		$this->executeQuery($addNewMigrationScript, 'failed to apply migration ' . $migrationDate);
	}

	private function triggerError(string $errorMessage = "")
	{
		trigger_error($errorMessage . $this->database->getErrorMessage(), E_USER_ERROR);
	}

	/**
	 * @throws \MigrationException
	 */
	private function createMigrationScriptsDir()
	{
		$path = $this->migrationDir;
		if (!file_exists($path) && !mkdir($path, 0777, true) && !is_dir($path))
		{
			throw new \MigrationException(sprintf('Directory "%s" was not created', $path));
		}
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

	public function updateDatabase()
	{
		$len = strlen(date(self::dateFormat));

		if (!Settings::getInstance()->isDev())
		{
			return;
		}
		$this->executeQuery($this->createTableScript, 'fail to create migration table ');

		$resultString = "";
		try
		{
			$result = $this->database->query($this->getLastMigrationScript);
			if ($result)
			{
				$resultString = $result->fetch_all()[0]['migration_name'];
				$resultString = $resultString ? : "";
			}
		}
		catch (\Exception $exception)
		{
			$this->triggerError('fail to get last migration script ');
		}
		$lastSuccessfulMigrationDate = $this->getLastSuccessfulMigrationDate($resultString, $len);
		$currentMigrationDate = $lastSuccessfulMigrationDate;
		$databaseMigrationDate = $lastSuccessfulMigrationDate;

		$this->createMigrationScriptsDir();
		$directoryIterator = new \RecursiveDirectoryIterator($this->migrationDir);
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
				try
				{
					$result = $this->database->multiQuery($query);
					if (!$result && $databaseMigrationDate !== $lastSuccessfulMigrationDate)
					{
						$this->writeLastMigrationRecord($currentMigrationDate);
					}
				}
				catch (\Exception $exception)
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

	/** Перед применением выполняет все предыдущие миграции*/
	public function addAndApplyMigration(string $changeDatabaseScript, string $migrationName)
	{
		if (!Settings::getInstance()->isDev())
		{
			return;
		}
		$this->updateDatabase();

		$this->executeQuery($changeDatabaseScript, 'failed to execute received script ');
		$this->addMigrationRecord($changeDatabaseScript, $migrationName);
	}

	public function addMigrationRecord(string $changeDatabaseScript, $migrationName)
	{
		$this->updateDatabase();

		$this->executeQuery($this->removeLastMigrationScript, 'failed to remove last migration script ');

		$time = $this->formatMigrationName();

		$addNewMigrationScript = "INSERT INTO up_migration (LAST_MIGRATION) VALUES ('$time')";
		$this->executeQuery($addNewMigrationScript, 'failed to add new migration script ' . $migrationName);
		$name = $this->formatMigrationName($migrationName);
		file_put_contents($_SERVER['DOCUMENT_ROOT'] . $this->migrationDir . $name . '.sql', $changeDatabaseScript);

	}
}



