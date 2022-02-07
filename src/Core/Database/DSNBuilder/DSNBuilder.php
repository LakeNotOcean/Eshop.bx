<?php

namespace Up\Core\Database\DSNBuilder;

use Up\Core\DatabaseConfig;

interface DSNBuilder
{
	public static function createDSN(DatabaseConfig $databaseConfig, array $options = []): string;
}