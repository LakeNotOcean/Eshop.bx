<?php

namespace Up\LayoutManager;

interface LayoutManagerInterface
{

	public function render(string $viewPath, array $viewParams): string;

}
