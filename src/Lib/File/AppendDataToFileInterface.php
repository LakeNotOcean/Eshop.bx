<?php

namespace Up\Lib\File;

interface AppendDataToFileInterface
{
	public static function push($data, string $path);
}