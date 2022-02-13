<?php

namespace Up\Controller;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessor;
use Up\Lib\Paginator\Paginator;
use Up\Service\ImageService\ImageServiceInterface;
use Up\Service\ItemService\ItemServiceInterface;


class ItemController
{
	protected $templateProcessor;
	protected $itemService;
	protected $imageService;
	protected $itemsInPage = 10;

	/**
	 * @param \Up\Core\TemplateProcessorImpl $templateProcessor
	 * @param \Up\Service\ItemService\ItemService $itemService
	 * @param \Up\Service\ImageService\ImageService $imageService
	 */
	public function __construct(
		TemplateProcessor     $templateProcessor,
		ItemServiceInterface  $itemService,
		ImageServiceInterface $imageService
	)
	{
		$this->templateProcessor = $templateProcessor;
		$this->itemService = $itemService;
		$this->imageService = $imageService;
	}

	/**
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
	public function getItems(Request $request): Response
	{
		if ($request->isQueryContains('page'))
		{
			$currentPage = (int)$request->getQueriesByName('page');
		}
		else
		{
			$currentPage = 1;
		}

		$items = $this->itemService->getItems(Paginator::getLimitOffset($currentPage, $this->itemsInPage));
		$itemsAmount = $this->itemService->getItemsAmount();
		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->itemsInPage);
		$pages = $this->templateProcessor->render(
			'catalog.php', [
							 'items' => $items,
							 'currentPage' => $currentPage,
							 'itemsAmount' => $itemsAmount,
							 'pagesAmount' => $pagesAmount,
						 ],
			'main.php',
			[]
		);

		return (new Response())->withBodyHTML($pages);
	}

	public function getItem(Request $request, $id): Response
	{
		$item = $this->itemService->getItemById($id);
		$pages = $this->templateProcessor->render('item.php', ['item' => $item], 'main.php', []);

		return (new Response())->withBodyHTML($pages);
	}
}
