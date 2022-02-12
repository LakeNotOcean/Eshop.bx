<?php

namespace Up\Core\DI;


class ImplementationsConfigPHP implements ImplementationsConfigInterface
{
	private $path;

	public function __construct(string $path)
	{
		$this->path = $path;
	}

	public function getImplementationsConfig(): array
	{
		require_once $this->path;

		/** @var array $implementations */
		return $implementations;
	}
}
