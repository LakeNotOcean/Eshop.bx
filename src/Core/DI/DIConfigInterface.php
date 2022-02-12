<?php

namespace Up\Core\DI;


interface DIConfigInterface
{
	public function getImplementations(): array;
	public function getSingletons(): array;
}
