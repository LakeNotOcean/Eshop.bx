<?php

namespace Up\Lib\File;

use InvalidArgumentException;
use JsonException;
use Up\Core\Error\FileDoesNotExistException;

class JsonFile implements AppendDataToFileInterface
{
	/**
	 * @param array $data
	 * @param string $path
	 *
	 * @return void
	 * @throws FileDoesNotExistException
	 * @throws JsonException
	 */
	public static function push($data, string $path): void
	{
		if (!(is_array($data)))
		{
			throw new InvalidArgumentException('Parameter $data must by array. Now: ' . gettype($data));
		}
		if (!file_exists($path))
		{
			throw new FileDoesNotExistException("File {$path} does not exist");
		}

		$fileContent = file_get_contents($path);
		$fileContent = empty($fileContent) ? '[' . PHP_EOL . ']' : $fileContent;

		// 512 is default depth
		$tempArray = json_decode($fileContent, true, 512, JSON_THROW_ON_ERROR);
		$tempArray[] = $data;
		$jsonData = json_encode($tempArray, JSON_THROW_ON_ERROR);
		file_put_contents($path, $jsonData);
	}
}