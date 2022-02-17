<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\EntityArray;
use Up\Entity\ItemDetail;
use Up\Entity\ItemsImage;
use Up\Entity\ItemType;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;
use Up\Lib\Paginator\Paginator;
use Up\Service\ImageService\ImageServiceInterface;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\TagService\TagService;
use Up\Service\TagService\TagServiceInterface;

class ItemController
{
	protected $templateProcessor;
	protected $itemService;
	protected $imageService;
	protected $tagService;
	protected $itemsInPage = 10;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\Service\ItemService\ItemService $itemService
	 * @param \Up\Service\ImageService\ImageService $imageService
	 * @param \Up\Service\TagService\TagService $tagService
	 */
	public function __construct(
		TemplateProcessorInterface $templateProcessor,
		ItemServiceInterface       $itemService,
		ImageServiceInterface      $imageService,
		TagServiceInterface        $tagService
	)
	{
		$this->templateProcessor = $templateProcessor;
		$this->itemService = $itemService;
		$this->imageService = $imageService;
		$this->tagService = $tagService;
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function getItems(Request $request): Response
	{
		$isAdmin = false;

		$currentPage = $request->containsQuery('page') ? (int)$request->getQueriesByName('page') : 1;
		$currentPage = $currentPage > 0 ? $currentPage : 1;
		$layout = $isAdmin ? 'layout/admin-main.php' : 'layout/main.php';
		if ($request->containsQuery('price') || $request->containsQuery('tag') || $request->containsQuery('spec'))
		{
			$query = $request->containsQuery('query') ? $request->getQueriesByName('query') : '';
			$price = $request->containsQuery('price') ? $request->getQueriesByName('price') : '';
			$tags = $request->containsQuery('tag') ? $request->getQueriesByName('tag') : [];
			$specs = $request->containsQuery('spec') ? $request->getQueriesByName('spec') : [];
			$items = $this->itemService->getItemsByFilters(Paginator::getLimitOffset($currentPage, $this->itemsInPage), $query,$price,$tags,$specs);
			$itemsAmount = $this->itemService->getItemsAmount($query);
		}
		elseif ($request->containsQuery('query'))
		{
			$query = $request->getQueriesByName('query');
			$items = $this->itemService->getItemsByQuery(Paginator::getLimitOffset($currentPage, $this->itemsInPage), $query);
			$itemsAmount = $this->itemService->getItemsAmount($query);
		}
		else
		{
			$items = $this->itemService->getItems(Paginator::getLimitOffset($currentPage, $this->itemsInPage));
			$itemsAmount = $this->itemService->getItemsAmount();
			$query = '';
		}
		$categories = $this->itemService->getItemsCategories();
		$tags = $this->itemService->getItemsTags();



		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->itemsInPage);
		$pages = $this->templateProcessor->render('catalog.php', [
			'items' => $items,
			'currentPage' => $currentPage,
			'itemsAmount' => $itemsAmount,
			'pagesAmount' => $pagesAmount,
			'query' => $query,
			'categories' => $categories,
			'tags' => $tags,
		],                                        $layout, ['query' => $query]);

		return (new Response())->withBodyHTML($pages);
	}

	public function getItem(Request $request, int $id): Response
	{
		$item = $this->itemService->getItemById($id);
		$pages = $this->templateProcessor->render('item.php', ['item' => $item], 'layout/main.php', []);

		return (new Response())->withBodyHTML($pages);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function addItem(Request $request): Response
	{
		$page = $this->templateProcessor->render('add-item.php', [], 'layout/admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function createNewItem(Request $request): Response
	{
		$item = new ItemDetail();

		$this->mapCommonItemInfoFromRequest($item, $request);

		$tagsString = $request->getPostParametersByName('item-tags');
		$tags = $this->tagService->save(array_map('trim', explode(',', $tagsString)));
		$item->setTags($tags);

		$categoriesArray = $request->getPostParametersByName('specs');
		foreach ($categoriesArray as $idCat => $categoryArray)
		{
			$category = new SpecificationCategory($idCat);
			foreach ($categoryArray as $idSpec => $specValue)
			{
				$specification = new Specification($idSpec);
				$specification->setValue($specValue);
				$category->setSpecification($specification);
			}
			$item->setSpecificationCategory($category);
		}

		$item->setMainImage(new ItemsImage(1, '1.png', true));

		$item->setImage(new ItemsImage(1, '1.png', true));

		$this->itemService->save($item);

		return (new Response())->withBodyHTML('');
	}

	private function mapCommonItemInfoFromRequest(ItemDetail $item, Request $request)
	{
		$item->setTitle($request->getPostParametersByName('item-title'));
		$item->setPrice($request->getPostParametersByName('item-price'));
		$item->setShortDescription($request->getPostParametersByName('item-short-description'));
		$item->setFullDescription($request->getPostParametersByName('item-full-description'));
		$item->setIsActive(true);
		$item->setSortOrder(3);
		$item->setItemType(new ItemType($request->getPostParametersByName('item-type'), ''));
	}
}
