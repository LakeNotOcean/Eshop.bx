<?php

namespace Up\Core\DI;


interface DIContainerInterface
{
	public function get(string $class);
}
