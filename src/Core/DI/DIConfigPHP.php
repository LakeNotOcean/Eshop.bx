<?php

namespace Up\Core\DI;


class DIConfigPHP implements DIConfigInterface
{
	private $path;

	public function __construct(string $path)
	{
		$this->path = $path;
	}

	public function getImplementations(): array
	{
		require $this->path;

		/** @var array $implementations */
		return $implementations;
	}

	public function getSingletons(): array
	{
		require $this->path;

		/** @var array $singletons */
		return $singletons;
	}
}
