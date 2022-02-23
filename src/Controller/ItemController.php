<?php

namespace Up\Controller;

use RuntimeException;
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
use Up\LayoutManager\LayoutManagerInterface;
use Up\LayoutManager\MainLayoutManager;
use Up\Lib\Paginator\Paginator;
use Up\Lib\Redirect;
use Up\Service\ImageService\ImageServiceInterface;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\TagService\TagServiceInterface;
use Up\Service\UserService\UserServiceInterface;


class ItemController
{
	protected $templateProcessor; //TODO remove
	protected $mainLayoutManager;
	protected $itemService;
	protected $imageService;
	protected $tagService;
	protected $itemsInPage = 10;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\LayoutManager\MainLayoutManager $mainLayoutManager
	 * @param \Up\Service\ItemService\ItemService $itemService
	 * @param \Up\Service\ImageService\ImageService $imageService
	 * @param \Up\Service\TagService\TagService $tagService
	 */
	public function __construct(
		TemplateProcessorInterface $templateProcessor,
		MainLayoutManager		   $mainLayoutManager,
		ItemServiceInterface       $itemService,
		ImageServiceInterface      $imageService,
		TagServiceInterface        $tagService
	)
	{
		$this->templateProcessor = $templateProcessor;
		$this->mainLayoutManager = $mainLayoutManager;
		$this->itemService = $itemService;
		$this->imageService = $imageService;
		$this->tagService = $tagService;
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function getItems(Request $request): Response
	{
		$isAdmin = $request->isAdmin();

		$deactivate = $request->containsQuery('deactivate_include') && $isAdmin;
		$query = $request->getQueriesOrDefaultList(['page'=> '1', 'query' => '', 'tag' => [], 'spec' => [], 'price' => '']);

		$currentPage = $query['page'] > 0 ? (int)$query['page'] : 1;

		$typeIds = $this->itemService->getTypeIdByQuery($query['query']);

		if (empty($typeIds))
		{
			$queryTypeId = 1;    //1 - значение передаваетмая через ручки.
		}
		elseif (count($typeIds) === 1)
		{
			$queryTypeId = $typeIds[0];
		}
		else
		{
			$queryTypeId = 0;
		}

		$items = $this->itemService->getItemsByFilters(Paginator::getLimitOffset($currentPage, $this->itemsInPage),
			$query['query'], $query['price'], $query['tag'], $query['spec'], $queryTypeId, $deactivate);

		$price = $this->itemService->getItemsMinMaxPriceByItemTypes($typeIds);
		$itemsAmount = $this->itemService->getItemsAmountByFilters($query['query'], $query['price'], $query['tag'], $query['spec'], $queryTypeId, $deactivate);
		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->itemsInPage);
		$userId = $request->getUser()->getId();

		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);

		$page = $this->mainLayoutManager
			->setQuery($query['query'])
			->render('catalog.php', [
			'items' => $this->itemService->mapItemsToUserItems($userId, $items),
			'itemsAmount' => $itemsAmount,
			'paginator' => $paginator,
			'query' => $query['query'],
			'price'=> $price,
			'tags' => $this->tagService->getTagsByItemType($queryTypeId),
			'categories' => $this->itemService->getItemsCategoriesByItemType($queryTypeId),
			'isAdmin' => ($request->getRouteName() === 'home-admin') ? $isAdmin : false
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function getFavoriteItems(Request $request): Response
	{
		$userId = $request->getUser()->getId();
		$currentPage = $request->containsQuery('page') ? (int)$request->getQueriesByName('page') : 1;
		$currentPage = $currentPage > 0 ? $currentPage : 1;
		$favoriteItems = $this->itemService->getFavoriteItems($userId, Paginator::getLimitOffset($currentPage, $this->itemsInPage));

		$itemsAmount = $this->itemService->getFavoriteItemsAmount($userId);
		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->itemsInPage);

		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);

		$page = $this->mainLayoutManager->render('favorites.php', [
			'favoriteItems' => $this->itemService->mapItemsToUserItems($userId, $favoriteItems),
			'paginator' => $paginator
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 * @throws RuntimeException
	 */
	public function addToFavorites(Request $request): Response
	{
		if (!$request->isAuthenticated())
		{
			throw new RuntimeException('Пользователь не авторизовн');
		}
		$userId = $request->getUser()->getId();
		$favoriteItemId = $request->getPostParametersByName('favorite-item-id');
		$this->itemService->addToFavorites($userId, $favoriteItemId);

		return (new Response())->withBodyHTML('');
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function removeFromFavorites(Request $request): Response
	{
		$userId = $request->getUser()->getId();
		if ($userId === 0)
		{
			throw new RuntimeException('Пользователь не авторизовн');
		}
		$favoriteItemId = $request->getPostParametersByName('favorite-item-id');
		$this->itemService->removeFromFavorites($userId, $favoriteItemId);
		return (new Response())->withBodyHTML('');
	}

	public function getItem(Request $request, int $id): Response
	{
		$userId = $request->getUser()->getId();
		$item = $this->itemService->getItemById($id);
		$itemsSimilar = $this->itemService->getItemsSimilarById($id,5);

		$page = $this->mainLayoutManager->render('item.php', [
			'item' => $this->itemService->mapItemDetailToUserItem($userId, $item),
			'similarItems' => $itemsSimilar,
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function addItem(Request $request, int $id = 0): Response
	{
		$page = $this->mainLayoutManager->render('add-item.php', [
			'item' => $id === 0 ? null : $this->itemService->getItemById($id)
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function updateItemPage(Request $request, int $id): Response
	{
		$item = $this->itemService->getItemById($id);
		$page = $this->mainLayoutManager->render('add-item.php', [
			'item' => $item
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

		$item = $this->itemService->save($item);
		if(!empty($imagesInfo))
		{
			$this->imageService->addImages($imagesInfo, $item);
		}

		return Redirect::createResponseByURLName('edit-item', [], ['id' => $item->getId()]);
	}

	public function acceptDeletion(Request $request, int $id): Response
	{
		$item = $this->itemService->getItemById($id);
		$page = $this->mainLayoutManager->render('accept-deletion-item.php', [
			'item' => $item
		]);
		return (new Response())->withBodyHTML($page);
	}

	public function realDeleteItem(Request $request, int $id): Response
	{
		$this->itemService->realDeleteItem($id);
		return Redirect::createResponseByURLName('home-admin');
	}

	public function deactivateItem(Request $request, int $id): Response
	{
		$this->itemService->deactivateItem($id);
		return (new Response())->withBodyHTML('');
	}

	public function activateItem(Request $request, int $id): Response
	{
		$this->itemService->activateItem($id);
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
