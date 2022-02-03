<?php

namespace Up\Core\DataBase\DSNBuilder;

use Up\Core\DatabaseConfig;

abstract class ConnectableDatabaseDSNBuilder implements DSNBuilder
{
	protected static $dnsDatabasePrefix;

	/**
	 * @param DatabaseConfig $databaseConfig
	 * @param array{charset:string} $options
	 *
	 * @return string
	 */
	public static function createDSN(DatabaseConfig $databaseConfig, array $options = []): string
	{
		$resultString = static::$dnsDatabasePrefix;

		$resultString .= self::getFormattedDNSOption('host', $databaseConfig->getHost());
		$resultString .= self::getFormattedDNSOption('dbname', $databaseConfig->getDbName());
		$resultString .= self::getFormattedDNSOption('port', (string)$databaseConfig->getPort());

		if (array_key_exists('charset', $options))
		{
			$resultString .= self::getFormattedDNSOption('charset', $options['charset']);
		}

		return $resultString;
	}

	protected static function getFormattedDNSOption(string $key, string $value): string
	{
		return "{$key}={$value};";
	}
}