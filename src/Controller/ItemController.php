<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\Item;
use Up\Entity\ItemDetail;
use Up\Entity\ItemType;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;
use Up\Entity\User\UserEnum;
use Up\Lib\Paginator\Paginator;
use Up\Lib\Redirect;
use Up\Service\ImageService\ImageServiceInterface;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\TagService\TagServiceInterface;
use Up\Service\UserService\UserServiceInterface;

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
		$isAuthenticated = $request->isAuthenticated();
		$isAdmin = $request->isAdmin();

		$queryTypeId = 1;
		$currentPage = $request->containsQuery('page') ? (int)$request->getQueriesByName('page') : 1;
		$currentPage = $currentPage > 0 ? $currentPage : 1;
		//Если пришел запрос с фильтров
		if ($request->containsQuery('price') || $request->containsQuery('tag') || $request->containsQuery('spec'))
		{

			$query = $request->containsQuery('query') ? $request->getQueriesByName('query') : '';
			$typeId = $this->itemService->getTypeIdByQuery($query);
			//Если запрос с поиском и поиск прошел по нескольким типам
			if ($query !== "" && count($typeId) > 1)
			{
				$queryTags = [];
				$querySpecs = [];
				$typeId[0] = 0;
				$categories = [];
				$tags = [];
				$price = $this->itemService->getItemsMinMaxPrice();
			}
			//Если запрос с поиском и посик только внутри одной категории
			else
			{
			$queryTags = $request->containsQuery('tag') ? $request->getQueriesByName('tag') : [];
			$querySpecs = $request->containsQuery('spec') ? $request->getQueriesByName('spec') : [];
			$categories = $this->itemService->getItemsCategoriesByItemType($typeId[0]);
			$tags = $this->tagService->getTagsByItemType($typeId[0]);
			$price = $this->itemService->getItemsMinMaxPriceByItemType($typeId[0]);
			}
			$queryPrice = $request->containsQuery('price') ? $request->getQueriesByName('price') : '';
			$items = $this->itemService->getItemsByFilters(Paginator::getLimitOffset($currentPage, $this->itemsInPage), $query,$queryPrice,$queryTags,$querySpecs,$typeId[0]);
			$itemsAmount = $this->itemService->getItemsAmountByFilters($query,$queryPrice,$queryTags,$querySpecs,);
		}
		//Если пришел запрос только с поиском
		elseif ($request->containsQuery('query'))
		{
			$query = $request->getQueriesByName('query');
			$items = $this->itemService->getItemsByQuery(Paginator::getLimitOffset($currentPage, $this->itemsInPage), $query);
			$itemsAmount = $this->itemService->getItemsAmount($query);
			$typeId = $this->itemService->getTypeIdByQuery($query);
			//Если поиск задел несколько категорий
			if (count($typeId) > 1)
			{
				$price = $this->itemService->getItemsMinMaxPrice();
				$categories = [];
				$tags = [];

			}
			else
				//Если поиск задел одну категорию
			{
				$categories = $this->itemService->getItemsCategoriesByItemType($typeId[0]);
				$tags = $this->tagService->getTagsByItemType($typeId[0]);
				$price = $this->itemService->getItemsMinMaxPriceByItemType($typeId[0]);
			}

		}
		else
			//Если поиск без запросов (просто переход по станице)
		{
			$items = $this->itemService->getItemsByTypeID(Paginator::getLimitOffset($currentPage, $this->itemsInPage),$queryTypeId);
			$itemsAmount = $this->itemService->getItemsAmount();
			$query = '';
			$categories = $this->itemService->getItemsCategoriesByItemType($queryTypeId);
			$tags = $this->tagService->getTagsByItemType($queryTypeId);
			$price = $this->itemService->getItemsMinMaxPriceByItemType($queryTypeId);
		}


		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->itemsInPage);
		$pages = $this->templateProcessor->render('catalog.php', [
			'items' => $items,
			'currentPage' => $currentPage,
			'itemsAmount' => $itemsAmount,
			'pagesAmount' => $pagesAmount,
			'query' => $query,
			'categories' => $categories,
			'tags' => $tags,
			'price'=> $price,
			'isAdmin' => ($request->getRouteName() === 'home-admin') ? $isAdmin : false
		], 'layout/main.php', [
			'isAuthenticated' => $isAuthenticated,
			'isAdmin' => $isAdmin,
			'query' => $query,
			'userName' => $request->getUser()->getName()
		]);

		return (new Response())->withBodyHTML($pages);
	}

	public function getItem(Request $request, int $id): Response
	{
		$item = $this->itemService->getItemById($id);
		$itemsSimilar = $this->itemService->getItemsSimilarById($id,5);
		$pages = $this->templateProcessor->render('item.php', [
			'item' => $item,
			'similarItems' => $itemsSimilar,
		], 'layout/main.php', [
			'isAuthenticated' => $request->isAuthenticated(),
			'isAdmin' => $request->isAdmin(),
			'userName' => $request->getUser()->getName()
		]);

		return (new Response())->withBodyHTML($pages);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function addItem(Request $request, int $id = 0): Response
	{
		$page = $this->templateProcessor->render('add-item.php', [
			'item' => $id === 0 ? null : $this->itemService->getItemById($id)
		], 'layout/main.php', [
			'isAuthenticated' => $request->isAuthenticated(),
			'isAdmin' => $request->isAdmin(),
			'userName' => $request->getUser()->getName()
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function updateItemPage(Request $request, int $id): Response
	{
		$item = $this->itemService->getItemById($id);
		$page = $this->templateProcessor->render('add-item.php', [
			'item' => $item
		], 'layout/main.php', [
			'isAuthenticated' => $request->isAuthenticated(),
			'isAdmin' => $request->isAdmin(),
			'userName' => $request->getUser()->getName()
		]);

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
		$imagesInfo = [];
		if($request->containsFile('main-image'))
		{
			$mainImage = $request->getFilesByName('main-image');
			$imagesInfo[] = ['name' => $mainImage['name'], 'type' => $mainImage['type'], 'tmp_name' => $mainImage['tmp_name'], 'is_main' => true];
		}
		$countOtherImages = 0;
		if($request->containsFile('other-images'))
		{
			$otherImages = $request->getFilesByName('other-images');
			$countOtherImages = count($otherImages['name']);
		}

		for($i = 0; $i < $countOtherImages; $i++)
		{
			$imagesInfo[] = ['name' => $otherImages['name'][$i], 'type' => $otherImages['type'][$i], 'tmp_name' => $otherImages['tmp_name'][$i], 'is_main' => false];
		}

		$itemId = $this->itemService->save($item)->getId();
		if(!empty($imagesInfo))
		{
			$this->imageService->addImages($imagesInfo, $itemId);
		}

		return Redirect::createResponseByURLName('edit-item', [], ['id' => $itemId]);
	}

	public function deactivateItem(Request $request, int $id): Response
	{
		$this->itemService->deactivateItem($id);
		return (new Response())->withBodyHTML('');
	}

	public function updateCommonInfo(Request $request): Response
	{
		$item = new Item();
		$item->setId($request->getPostParametersByName('item-id'));
		$item->setSortOrder($request->getPostParametersByName('item-sort_order'));
		$item->setShortDescription($request->getPostParametersByName('item-short-description'));
		$item->setPrice($request->getPostParametersByName('item-price'));
		$item->setTitle($request->getPostParametersByName('item-title'));
		$this->itemService->updateCommonInfo($item);
		$response = new Response();
		return $response->withBodyHTML('');
	}

	private function mapCommonItemInfoFromRequest(ItemDetail $item, Request $request)
	{
		$item->setTitle($request->getPostParametersByName('item-title'));
		$item->setPrice($request->getPostParametersByName('item-price'));
		$item->setShortDescription($request->getPostParametersByName('item-short-description'));
		$item->setFullDescription($request->getPostParametersByName('item-full-description'));
		$item->setIsActive(true);
		$item->setSortOrder($request->getPostParametersByName('item-sort_order'));
		$item->setItemType(new ItemType($request->getPostParametersByName('item-type'), ''));
		if ($request->containsPost('item-id'))
		{
			$item->setId($request->getPostParametersByName('item-id'));
		}
	}

}
