<?php

namespace Up\Core\DataBase\DSNBuilder;

class MysqlDSNBuilder extends ConnectableDatabaseDSNBuilder
{
	protected static $dnsDatabasePrefix = 'mysql:';
}