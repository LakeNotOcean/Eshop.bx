<?php

namespace Up\Controller;

use RuntimeException;
use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\DAO\OrderDAO\OrderDAOInterface;
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
use Up\Service\CartService\CartServiceInterface;
use Up\Service\ImageService\ImageServiceInterface;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\OrderService\OrderServiceInterface;
use Up\Service\ReviewService\ReviewService;
use Up\Service\ReviewService\ReviewServiceInterface;
use Up\Service\TagService\TagServiceInterface;
use Up\Service\UserService\UserServiceInterface;

class ItemController
{
	protected $templateProcessor;
	protected $mainLayoutManager;
	protected $itemService;
	protected $imageService;
	protected $tagService;
	protected $reviewService;
	protected $orderService;
	protected $cartService;

	protected $typesInPage = 3;
	protected $itemsInPage = 10;
	protected $reviewsInMorePage = 10;
	protected $reviewsInItemPage = 3;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\LayoutManager\MainLayoutManager $mainLayoutManager
	 * @param \Up\Service\ItemService\ItemService $itemService
	 * @param \Up\Service\ImageService\ImageService $imageService
	 * @param \Up\Service\TagService\TagService $tagService
	 * @param \Up\Service\ReviewService\ReviewService $reviewService
	 * @param \Up\Service\OrderService\OrderService $orderService
	 * @param \Up\Service\CartService\CartService $cartService
	 */
	public function __construct(
		TemplateProcessorInterface $templateProcessor,
		MainLayoutManager          $mainLayoutManager,
		ItemServiceInterface       $itemService,
		ImageServiceInterface      $imageService,
		TagServiceInterface        $tagService,
		ReviewServiceInterface     $reviewService,
		OrderServiceInterface      $orderService,
		CartServiceInterface       $cartService
	)
	{
		$this->templateProcessor = $templateProcessor;
		$this->mainLayoutManager = $mainLayoutManager;
		$this->itemService = $itemService;
		$this->imageService = $imageService;
		$this->tagService = $tagService;
		$this->reviewService = $reviewService;
		$this->orderService = $orderService;
		$this->cartService = $cartService;
	}


	public function setTypeItem(Request $request): Response
	{
		$query = $request->getQueriesOrDefaultList(
			['page' => '1']);
		$currentPage = ($query['page'] > 1) ? $query['page'] : 1;
		$types = $this->itemService->getTypes(Paginator::getLimitOffset($currentPage,$this->typesInPage));
		$typesWithItems = [];
		foreach ($types as $type) {
			$item = $this->itemService->getFirstItemOfType($type);
			if (isset($item))
			{
				$typesWithItems[] = [
					'item' => $item,
					'type' => $type
				];
			}
		}

		$typesAmount = $this->itemService->getTypesAmount();
		$pagesAmount = Paginator::getPageCount($typesAmount,$this->typesInPage);
		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);
		$page = $this->mainLayoutManager->render('change-type.php', [
			'typesWithItems' => $typesWithItems,
			'paginator' => $paginator,
		]);

		return (new Response())->withBodyHTML($page);
	}


	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function getItems(Request $request): Response
	{
		$isAdmin = $request->isAdmin();

		$deactivate = $request->containsQuery('deactivate_include') && $isAdmin;
		$query = $request->getQueriesOrDefaultList(
			['page' => '1', 'query' => '', 'tag' => [], 'spec' => [], 'price' => '', 'sorting' => 'sort_order', 'type' => 0]
		);

		$currentPage = $query['page'] > 0 ? (int)$query['page'] : 1;

		$sortingMethods = [
			'sort_order' => 'SORT_ORDER DESC',
			'price' => 'PRICE',
			'price_desc' => 'PRICE DESC',
			'name' => 'TITLE',
			'name_desc' => 'TITLE DESC',
		];
		$sortingMethod = $sortingMethods[$query['sorting']];

		$typeIds = $this->itemService->getTypeIdByQuery($query['query']);
		if (empty($typeIds))
		{
			$queryTypeId = $query['type'];    //1 - значение передаваемое через ручки.
		}
		elseif (count($typeIds) === 1)
		{
			$queryTypeId = $typeIds[0];
		}
		else
		{
			$queryTypeId = 0;
		}

		$items = $this->itemService->getItemsByFilters(
			Paginator::getLimitOffset($currentPage, $this->itemsInPage),
			$query['query'],
			$query['price'],
			$query['tag'],
			$query['spec'],
			$queryTypeId,
			$deactivate,
			$sortingMethod
		);

		$price = $this->itemService->getItemsMinMaxPriceByItemTypes($typeIds);
		$itemsAmount = $this->itemService->getItemsAmountByFilters(
			$query['query'],
			$query['price'],
			$query['tag'],
			$query['spec'],
			$queryTypeId,
			$deactivate
		);
		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->itemsInPage);
		$userId = $request->getUser()->getId();

		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);

		$page = $this->mainLayoutManager->setQuery($query['query'])->render('catalog.php', [
				'items' => $this->itemService->mapItemsToUserItems($userId, $items),
				'itemsAmount' => $itemsAmount,
				'paginator' => $paginator,
				'query' => $query['query'],
				'price' => $price,
				'tags' => $this->tagService->getTagsByItemType($queryTypeId),
				'categories' => $this->itemService->getItemsCategoriesByItemType($queryTypeId),
				'isAdmin' => ($request->getRouteName() === 'home-admin') ? $isAdmin : false,
				'sortingMethod' => $query['sorting'],
			]);

		return (new Response())->withBodyHTML($page);
	}

	public function getFavoriteItems(Request $request): Response
	{
		$userId = $request->getUser()->getId();
		$currentPage = $request->containsQuery('page') ? (int)$request->getQueriesByName('page') : 1;
		$currentPage = $currentPage > 0 ? $currentPage : 1;
		$favoriteItems = $this->itemService->getFavoriteItems(
			$userId,
			Paginator::getLimitOffset(
				$currentPage,
				$this->itemsInPage
			)
		);

		$itemsAmount = $this->itemService->getFavoriteItemsAmount($userId);
		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->itemsInPage);

		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);

		$page = $this->mainLayoutManager->render('favorites.php', [
			'favoriteItems' => $this->itemService->mapItemsToUserItems($userId, $favoriteItems),
			'paginator' => $paginator,
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

	public function moreReviews(Request $request, int $id): Response
	{
		$query = $request->getQueriesOrDefaultList(['page' => '1', 'query' => '']);
		$currentPage = $query['page'] > 0 ? (int)$query['page'] : 1;
		$reviews = $this->reviewService->getReviewsByItemId(
			Paginator::getLimitOffset($currentPage, $this->reviewsInMorePage),
			$id
		);
		$item = $this->itemService->getItemById($id);
		$currentUser = $request->getUser();
		$item = $this->itemService->mapItemDetailToUserItem($currentUser->getId(), $item);

		$reviewsAmount = $item->getAmountReviews();
		$pagesAmount = Paginator::getPageCount($reviewsAmount, $this->reviewsInMorePage);
		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);

		$page = $this->mainLayoutManager->setQuery($query['query'])
			->render('more-reviews.php', [
				'paginator' => $paginator,
				'item' => $item,
				'user' => $currentUser,
				'reviews' => $reviews
			]);
		return (new Response())->withBodyHTML($page);
	}

	public function myPurchased(Request $request): Response
	{
		$query = $request->getQueriesOrDefaultList(['page' => '1', 'query' => '']);
		$currentPage = $query['page'] > 0 ? (int)$query['page'] : 1;
		$userId = $request->getUser()->getId();
		$itemsAmount = $this->itemService->getAmountPurchasedItems($userId);
		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->itemsInPage);
		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);

		$items = $this->itemService->getPurchasedItems($userId, Paginator::getLimitOffset($currentPage, $itemsAmount));
		$items = $this->itemService->mapItemsToUserItems($userId, $items);
		$itemsIds = array_values(array_map(function(Item $item){return $item->getId();}, $items));
		$reviews = $this->reviewService->getUsersReviewsByItemIds($userId, $itemsIds);
		$page = $this->mainLayoutManager->setQuery($query['query'])->render('my-purchased.php', [
			'paginator' => $paginator,
			'reviews' => $reviews,
			'items' => $items
		]);
		return (new Response())->withBodyHTML($page);
	}

	public function getItem(Request $request, int $id): Response
	{
		$userId = $request->getUser()->getId();
		$item = $this->itemService->getItemById($id);
		$itemIsPurchased = $this->orderService->checkThatUserBoughtItem($userId, $id);
		$reviewIsWritten = $this->reviewService->existReviewByUserAndItemIds($userId, $id);
		$itemsSimilar = $this->itemService->getItemsSimilarById($id, 5);
		$reviews = $this->reviewService->getReviewsByItemId(Paginator::getLimitOffset(1, $this->reviewsInItemPage), $id);
		$page = $this->mainLayoutManager->render('item.php', [
			'item' => $this->itemService->mapItemDetailToUserItem($userId, $item),
			'similarItems' => $itemsSimilar,
			'reviews' => $reviews,
			'isItemAdded' => $this->cartService->isItemInCart($item->getId()),
			'itemIsPurchased' => $itemIsPurchased,
			'reviewIsWritten' => $reviewIsWritten,
			'isAuthenticated' => $request->isAuthenticated(),
			'user' => $request->getUser()
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
			'item' => $item,
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
		$tags = $this->tagService->save(array_map('trim', explode(',', $tagsString)), $item->getItemType()->getId());
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
		if ($request->containsFile('main-image'))
		{
			$mainImage = $request->getFilesByName('main-image');
			$imagesInfo[] = [
				'name' => $mainImage['name'],
				'type' => $mainImage['type'],
				'tmp_name' => $mainImage['tmp_name'],
				'is_main' => true,
			];
		}
		$countOtherImages = 0;
		if ($request->containsFile('other-images'))
		{
			$otherImages = $request->getFilesByName('other-images');
			$countOtherImages = count($otherImages['name']);
		}

		for ($i = 0; $i < $countOtherImages; $i++)
		{
			$imagesInfo[] = [
				'name' => $otherImages['name'][$i],
				'type' => $otherImages['type'][$i],
				'tmp_name' => $otherImages['tmp_name'][$i],
				'is_main' => false,
			];
		}

		$item = $this->itemService->save($item);
		if (!empty($imagesInfo))
		{
			$this->imageService->addImages($imagesInfo, $item);
		}

		return Redirect::createResponseByURLName('edit-item', [], ['id' => $item->getId()]);
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
