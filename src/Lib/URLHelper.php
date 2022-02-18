<?php

namespace Up\Lib;

class URLHelper
{
	public static function removeIfExistGetParametersFromPath(string $path): string
	{
		if (($queryParamsIndex = strpos($path, '?')))
		{
			$path = mb_substr($path, 0, $queryParamsIndex);
		}

		return $path;
	}

	public static function urlContainsParam(string $url, string $paramKey)
	{
		$query = parse_url($url, PHP_URL_QUERY);

		if (empty($query))
		{
			return false;
		}
		$queryParams = explode('&', $query);
		foreach ($queryParams as $queryParam)
		{
			$keyValue = explode('=', $queryParam);
			if ($paramKey === $keyValue[0])
			{
				return true;
			}
		}

		return false;
	}
}