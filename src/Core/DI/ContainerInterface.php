<?php

namespace Up\Core\DI;

interface ContainerInterface
{
	public function get(string $name);

	public function has(string $name): bool;
}