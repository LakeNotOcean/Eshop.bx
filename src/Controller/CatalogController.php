<?php

namespace Up\Controller;

use Up\Service\CatalogService;

function renderTemplate(string $path, array $templateData = []): string
{
	if (!file_exists($path))
	{
		return "";
	}

	extract($templateData, EXTR_OVERWRITE);

	ob_start();

	include $path;

	return ob_get_clean();
}

class CatalogController
{
	private string $path = '../src/layout/';
	private CatalogService $catalogService;

	public function __construct()
	{
		$this->catalogService = new CatalogService();
	}

	public function getTemplate(): string
	{
		$content = renderTemplate($this->path . 'catalog.php', [
			'result_count' => $this->getResultCount(),
			'items' => $this->getItems(),
		]);

		return renderTemplate($this->path . 'main.php', [
			'content' => $content,
		]);
	}

	private function getItems(): array
	{
		return $this->catalogService->getItems();
	}

	private function getResultCount(): int
	{
		return $this->catalogService->getResultCount();
	}

}
