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

		$currentPage = $request->containsQuery('page') ? (int)$request->getQueriesByName('page') : 1;
		$currentPage = $currentPage > 0 ? $currentPage : 1;
		if ($request->containsQuery('price') || $request->containsQuery('tag') || $request->containsQuery('spec') || $request->containsQuery('deactivate_include'))
		{
			$query = $request->containsQuery('query') ? $request->getQueriesByName('query') : '';
			$price = $request->containsQuery('price') ? $request->getQueriesByName('price') : '';
			$tags = $request->containsQuery('tag') ? $request->getQueriesByName('tag') : [];
			$specs = $request->containsQuery('spec') ? $request->getQueriesByName('spec') : [];
			$deactivate = $request->containsQuery('deactivate_include') && $isAdmin;
			$items = $this->itemService->getItemsByFilters(Paginator::getLimitOffset($currentPage, $this->itemsInPage), $query,$price,$tags,$specs, $deactivate);
			$itemsAmount = $this->itemService->getItemsAmountByFilters($query,$price,$tags,$specs, $deactivate);
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
		$price = $this->itemService->getItemsMinMaxPrice();

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

	public function getFavoriteItems(Request $request): Response
	{
		$userId = $request->getUser()->getId();
		$currentPage = $request->containsQuery('page') ? (int)$request->getQueriesByName('page') : 1;
		$currentPage = $currentPage > 0 ? $currentPage : 1;
		$favoriteItems = $this->itemService->getFavoriteItems($userId, Paginator::getLimitOffset($currentPage, $this->itemsInPage));

		$itemsAmount = $this->itemService->getFavoriteItemsAmount($userId);
		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->itemsInPage);

		$page = $this->templateProcessor->render('favorites.php', [
			'favoriteItems' => $favoriteItems,
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount
		], 'layout/main.php', [
			 'isAuthenticated' => $request->isAuthenticated(),
			 'isAdmin' => $request->isAdmin(),
			 'userName' => $request->getUser()->getName()
		 ]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 * @throws RuntimeException
	 */
	public function addToFavorites(Request $request): Response
	{
		$userId = $request->getUser()->getId();
		if ($userId === 0)
		{
			throw new RuntimeException('Пользователь не авторизовн');
		}
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
		$favoriteItemId = $request->getPostParametersByName('favorite-item-id');
		$this->itemService->removeFromFavorites($userId, $favoriteItemId);
		return (new Response())->withBodyHTML('');
	}

	public function getItem(Request $request, int $id): Response
	{
		$item = $this->itemService->getItemById($id);
		$itemsSimilar = $this->itemService->getItemsSimilarById($id,5);
		$page = $this->templateProcessor->render('item.php', [
			'item' => $item,
			'similarItems' => $itemsSimilar,
		], 'layout/main.php', [
			'isAuthenticated' => $request->isAuthenticated(),
			'isAdmin' => $request->isAdmin(),
			'userName' => $request->getUser()->getName()
		]);

		return (new Response())->withBodyHTML($page);
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
