<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;
use Up\LayoutManager\MainLayoutManager;
use Up\Service\SpecificationService\SpecificationsServiceInterface;


class CategoryController
{
	protected $mainLayoutManager;
	protected $specificationsService;

	/**
	 * @param \Up\LayoutManager\MainLayoutManager $mainLayoutManager
	 * @param \Up\Service\SpecificationService\SpecificationsService $specificationsService
	 * @param \Up\Service\UserService\UserService $specificationsService
	 */
	public function __construct(
		MainLayoutManager 			   $mainLayoutManager,
		SpecificationsServiceInterface $specificationsService
	)
	{
		$this->specificationsService = $specificationsService;
		$this->mainLayoutManager = $mainLayoutManager;
	}

	public function chooseItemType(Request $request): Response
	{
		$itemTypes = $this->specificationsService->getItemTypes();
		$page = $this->mainLayoutManager->render('choose-item-type.php', [
			'itemTypes' => $itemTypes
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function addItemType(Request $request): Response
	{
		$categories = $this->specificationsService->getCategoriesWithSpecifications();
		$page = $this->mainLayoutManager->render('add-item-type.php', [
			'categories' => $categories
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function addItemTypeAndSaveToDB(Request $request): Response
	{
		$itemTypeName = $request->getPostParametersByName('item-type');
		$itemTypeName = trim($itemTypeName);

		if ($request->containsPost('template-specs'))
		{
			$templateSpecs = $request->getPostParametersByName('template-specs');
		}

		if (!empty($templateSpecs) && !empty($itemTypeName))
		{
			$this->specificationsService->addItemType($itemTypeName, $templateSpecs);
		}

		$message = '';
		if (empty($itemTypeName))
		{
			$message = 'Не указано название типа товаров';
		}
		elseif (empty($templateSpecs))
		{
			$message = 'Не выбраны спецификации типа товаров';
		}

		$categories = $this->specificationsService->getCategoriesWithSpecifications();
		$page = $this->mainLayoutManager->render('add-item-type.php', [
			'categories' => $categories,
			'message' => $message
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function addCategory(Request $request): Response
	{
		$page = $this->mainLayoutManager->render('add-category.php', []);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 * @throws \Exception
	 */
	public function addCategoryAndSaveToDB(Request $request): Response
	{
		$category = $request->getPostParametersByName('category');
		$category = trim($category);
		$categoryOrder = (int) $request->getPostParametersByName('category-order');

		$message = '';
		if (!empty($category))
		{
			$newCategory = new SpecificationCategory(0, $category, $categoryOrder);
			$this->specificationsService->addCategory($newCategory);
		}
		if (empty($category))
		{
			$message = 'Не заполнено название категории';
		}

		$page = $this->mainLayoutManager->render('add-category.php', [
			'message' => $message
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function addSpecification(Request $request): Response
	{
		$categories = $this->specificationsService->getCategories();
		$page = $this->mainLayoutManager->render('add-specification.php', [
			'categories' => $categories
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function addSpecificationAndSaveToDB(Request $request): Response
	{
		$categoryId = $request->getPostParametersByName('category-id');
		$specName = $request->getPostParametersByName('spec-name');
		$specName = trim($specName);
		$specOrder = (int) $request->getPostParametersByName('spec-order');

		$message = '';
		if (!empty($specName))
		{
			$newSpec = new Specification(0, $specName, $specOrder);
			$this->specificationsService->addSpecification($categoryId, $newSpec);
		}
		if (empty($specName))
		{
			$message = 'Не заполнено название спецификации';
		}

		$categories = $this->specificationsService->getCategories();
		$page = $this->mainLayoutManager->render('add-specification.php', [
			'categories' => $categories,
			'message' => $message
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function getCategoriesJSON(Request $request): Response
	{
		return (new Response())->withBodyJSON(array_map(function(SpecificationCategory $spec){
			return $spec->getName();
		}, $this->specificationsService->getCategories()));
	}

	public function getCategoriesWithSpecsJSON(Request $request): Response
	{
		return (new Response())->withBodyJSON(array_map(function(SpecificationCategory $cat){
			return [$cat->getName(), array_map(function(Specification $spec){
				return $spec->getName();
			}, $cat->getSpecifications())];
		}, $this->specificationsService->getCategoriesWithSpecifications()));
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function getCategoriesByItemTypeIdJSON(Request $request): Response
	{
		$categories = $this->specificationsService->getCategoriesByItemTypeId($request->getQueriesByName('item-type'));
		$categoriesArray = array_map(function(SpecificationCategory $cat){
			return [$cat->getName() ,array_map(function(Specification $spec){
				return $spec->getName();
			}, $cat->getSpecifications())];
		}, $categories);

		return (new Response())->withBodyJSON($categoriesArray);
	}

	public function deleteCategoryPage(Request $request): Response
	{
		$categories = $this->specificationsService->getCategories();
		$page = $this->mainLayoutManager->render('delete-category.php', [
			'categories' => $categories,
			'isCategoryDeleted' => false
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function deleteCategory(Request $request): Response
	{
		$id = $request->getPostParametersByName('category-id');
		$this->specificationsService->deleteCategoryById($id);
		$categories = $this->specificationsService->getCategories();
		$page = $this->mainLayoutManager->render('delete-category.php', [
			'categories' => $categories,
			'isCategoryDeleted' => true
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function chooseCategoryToSpecDelete(Request $request): Response
	{
		$categories = $this->specificationsService->getCategories();
		$page = $this->mainLayoutManager->render('choose-category-specs-delete.php', [
			'categories' => $categories
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function deleteSpecPage(Request $request, int $id): Response
	{
		$specifications = $this->specificationsService->getSpecificationByCategoryId($id);
		$page = $this->mainLayoutManager->render('delete-specification.php', [
			'specifications' => $specifications,
			'categoryId' => $id,
			'isSpecificationDeleted' => false
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function deleteSpec(Request $request): Response
	{
		$specId = $request->getPostParametersByName('specification-id');
		$categoryId = $request->getPostParametersByName('category-id');
		$this->specificationsService->deleteSpecificationById($specId);
		$specifications = $this->specificationsService->getSpecificationByCategoryId($categoryId);
		$page = $this->mainLayoutManager->render('delete-specification.php', [
			'specifications' => $specifications,
			'categoryId' => $categoryId,
			'isSpecificationDeleted' => true
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function getCategoriesByItemIdJSON(Request $request, int $id): Response
	{
		$categories = $this->specificationsService->getItemCategoriesByItemId($id);
		$categoriesArray = array_map(function(SpecificationCategory $cat){
			return [$cat->getName() ,array_map(function(Specification $spec){
				return [$spec->getName(), $spec->getValue()];
			}, $cat->getSpecifications())];
		}, $categories);
		return (new Response())->withBodyJSON($categoriesArray);
	}

}
