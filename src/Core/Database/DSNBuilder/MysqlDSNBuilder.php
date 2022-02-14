<?php

namespace Up\Core\Database\DSNBuilder;


class MysqlDSNBuilder extends ConnectableDatabaseDSNBuilder
{
	protected static $dnsDatabasePrefix = 'mysql:';
}
