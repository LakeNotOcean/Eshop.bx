<?php

namespace Up\Core\DI;

class DIConfigPHP implements DIConfigInterface
{
	private $path = '';

	public function __construct(string $path)
	{
		$this->path = $path;
	}

	public function getConfig(): array
	{
		require_once $this->path;
		/** @var array $config */
		return $config;
	}
}