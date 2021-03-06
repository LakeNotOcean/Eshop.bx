<?php

namespace Up\LayoutManager;

use Up\Core\Message\Request;
use Up\Core\TemplateProcessorInterface;
use Up\Service\CartService\CartServiceInterface;

class MainLayoutManager implements LayoutManagerInterface
{

	protected $templateProcessor;
	protected $cartService;
	protected $request;
	protected $query = '';
	protected const path = 'layout/main.php';

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\Service\CartService\CartService $cartService
	 * @param \Up\Core\Message\Request $request
	 */
	public function __construct(
		TemplateProcessorInterface $templateProcessor,
		CartServiceInterface $cartService,
		Request $request)
	{
		$this->templateProcessor = $templateProcessor;
		$this->cartService = $cartService;
		$this->request = $request;
	}

	public function render(string $viewPath, array $viewParams): string
	{
		return $this->templateProcessor->render($viewPath, $viewParams, $this::path, $this->getLayoutParams());
	}

	public function setQuery(string $query): self
	{
		$this->query = $query;
		return $this;
	}

	protected function getLayoutParams(): array
	{
		return [
			'darkMode' => $this->getDarkMode(),
			'isAuthenticated' => $this->request->isAuthenticated(),
			'role' => $this->request->getUser()->getRole()->getName(),
			'query' => $this->query,
			'userName' => $this->request->getUser()->getName(),
			'cartSize' => $this->cartService->getCartSize()
		];
	}

	protected function getDarkMode(): bool
	{
		$darkMode = false;
		if ($this->request->containsCookie('darkMode'))
		{
			$darkMode = $this->request->getCookiesParametersByName('darkMode');
		}
		return $darkMode;
	}

}
