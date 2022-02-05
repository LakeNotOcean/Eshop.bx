<?php

namespace Up\Core\DI;

interface DIConfigInterface
{
	public function getConfig(string $path): array;
}