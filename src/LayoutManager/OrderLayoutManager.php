<?php

namespace Up\LayoutManager;

use Up\Core\Message\Request;
use Up\Core\TemplateProcessorInterface;

class OrderLayoutManager  implements LayoutManagerInterface
{

	protected $templateProcessor;
	protected $request;
	protected $orderItems = [];
	protected const path = 'layout/order.php';

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
			'cost' => $this->calculateTotalCost($this->orderItems),
			'orderSize' => count($this->orderItems)
		];
	}

	public function setOrderItems(array $orderItems): self
	{
		$this->orderItems = $orderItems;
		return $this;
	}

	private function calculateTotalCost(array $items): int
	{
		$cost = 0;
		foreach ($items as $item)
		{
			$cost += $item->getPrice();
		}
		return $cost;
	}

}
