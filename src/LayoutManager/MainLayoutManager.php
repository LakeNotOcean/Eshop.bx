<?php

namespace Up\LayoutManager;

use Up\Core\Message\Request;
use Up\Core\TemplateProcessorInterface;

class MainLayoutManager implements LayoutManagerInterface
{

	protected $templateProcessor;
	protected $request;
	protected $query = '';
	protected const path = 'layout/main.php';

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\Core\Message\Request $request
	 */
	public function __construct(TemplateProcessorInterface $templateProcessor, Request $request)
	{
		$this->templateProcessor = $templateProcessor;
		$this->request = $request;
	}

	public function render(string $viewPath, array $viewParams): string
	{
		return $this->templateProcessor->render($viewPath, $viewParams, $this::path, $this->getLayoutParams());
	}

	protected function getLayoutParams(): array
	{
		return [
			'isAuthenticated' => $this->request->isAuthenticated(),
			'isAdmin' => $this->request->isAdmin(),
			'query' => $this->query,
			'userName' => $this->request->getUser()->getName()
		];
	}

	public function setQuery(string $query): self
	{
		$this->query = $query;
		return $this;
	}

}