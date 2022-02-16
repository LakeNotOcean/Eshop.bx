<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;
use Up\Service\SpecificationService\SpecificationsServiceInterface;


class CategoryController
{
	protected $templateProcessor;
	protected $specificationsService;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\Service\SpecificationService\SpecificationsService $specificationsService
	 */
	public function __construct(TemplateProcessorInterface     $templateProcessor,
								SpecificationsServiceInterface $specificationsService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->specificationsService = $specificationsService;
	}

	public function chooseItemType(Request $request): Response
	{
		$itemTypes = $this->specificationsService->getItemTypes();
		$page = $this->templateProcessor->render('choose-item-type.php', [
			'itemTypes' => $itemTypes
		], 'layout/admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	public function addItemType(Request $request): Response
	{
		$categories = $this->specificationsService->getCategoriesWithSpecifications();
		$page = $this->templateProcessor->render('add-item-type.php', [
			'categories' => $categories,
			'isNewItemTypeAdded' => false
		], 'layout/admin-main.php', []);

		$response = new Response();


		return $response->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function addItemTypeAndSaveToDB(Request $request): Response
	{
		$itemTypeName = $request->getPostParametersByName('item-type');
		$templateSpecs = $request->getPostParametersByName('template-specs');
		$this->specificationsService->addItemType($itemTypeName, $templateSpecs);

		$categories = $this->specificationsService->getCategoriesWithSpecifications();
		$page = $this->templateProcessor->render('add-item-type.php', [
			'categories' => $categories,
			'isNewItemTypeAdded' => true
		], 'layout/admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	public function addCategory(Request $request): Response
	{
		$page = $this->templateProcessor->render('add-category.php', [
			'isNewCategoryAdded' => false
		], 'layout/admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function addCategoryAndSaveToDB(Request $request): Response
	{
		$category = $request->getPostParametersByName('category');
		$categoryOrder = $request->getPostParametersByName('category-order');
		$newCategory = new SpecificationCategory(0, $category, $categoryOrder);
		$this->specificationsService->addCategory($newCategory);
		$page = $this->templateProcessor->render('add-category.php', [
			'isNewCategoryAdded' => true
		], 'layout/admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	public function addSpecification(Request $request): Response
	{
		$categories = $this->specificationsService->getCategories();
		$page = $this->templateProcessor->render('add-specification.php', [
			'categories' => $categories,
			'isNewSpecAdded' => false
		], 'layout/admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function addSpecificationAndSaveToDB(Request $request): Response
	{
		$categoryId = $request->getPostParametersByName('category-id');
		$specName = $request->getPostParametersByName('spec-name');
		$specOrder = $request->getPostParametersByName('spec-order');
		$newSpec = new Specification(0, $specName, $specOrder);
		$this->specificationsService->addSpecification($categoryId, $newSpec);

		$categories = $this->specificationsService->getCategories();
		$page = $this->templateProcessor->render('add-specification.php', [
			'categories' => $categories,
			'isNewSpecAdded' => true
		], 'layout/admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	public function getCategoriesJSON(Request $request): Response
	{
		$response = new Response();
		$response = $response->withBodyJSON(array_map(function(SpecificationCategory $spec){
			return $spec->getName();
		},$this->specificationsService->getCategories()));
		return $response;
	}

	public function getCategoriesWithSpecsJSON(Request $request): Response
	{
		$response = new Response();
		$response = $response->withBodyJSON(array_map(function(SpecificationCategory $cat){
			return [$cat->getName(), array_map(function(Specification $spec){
				return $spec->getName();
			}, $cat->getSpecifications())];
		}, $this->specificationsService->getCategoriesWithSpecifications()));
		return $response;
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function getCategoriesByItemTypeIdJSON(Request $request): Response
	{
		$response = new Response();
		$categories = $this->specificationsService->getCategoriesByItemTypeId($request->getQueriesByName('item-type'));
		$categoriesArray = array_map(function(SpecificationCategory $cat){
			return [$cat->getName() ,array_map(function(Specification $spec){
				return $spec->getName();
			}, $cat->getSpecifications())];
		}, $categories);
		return $response->withBodyJSON($categoriesArray);
	}

	public function deleteCategoryPage(Request $request): Response
	{
		$response = new Response();
		$categories = $this->specificationsService->getCategories();
		$page = $this->templateProcessor->render('delete-category.php', [
			'categories' => $categories,
			'isCategoryDeleted' => false
		], 'layout/admin-main.php', []);
		return $response->withBodyHTML($page);
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function deleteCategory(Request $request): Response
	{
		$response = new Response();
		$id = $request->getPostParametersByName('category-id');
		$this->specificationsService->deleteCategoryById($id);
		$categories = $this->specificationsService->getCategories();
		$page = $this->templateProcessor->render('delete-category.php', [
			'categories' => $categories,
			'isCategoryDeleted' => true
		], 'layout/admin-main.php', []);
		return $response->withBodyHTML($page);
	}

	public function chooseCategoryToSpecDelete(Request $request): Response
	{
		$response = new Response();
		$categories = $this->specificationsService->getCategories();
		$page = $this->templateProcessor->render('choose-category-specs-delete.php', [
			'categories' => $categories
		], 'layout/admin-main.php', []);
		return $response->withBodyHTML($page);
	}

	public function deleteSpecPage(Request $request, int $id): Response
	{
		$response = new Response();
		$specifications = $this->specificationsService->getSpecificationByCategoryId($id);
		$page = $this->templateProcessor->render('delete-specification.php', [
			'specifications' => $specifications,
			'categoryId' => $id,
			'isSpecificationDeleted' => false
		], 'layout/admin-main.php', []);
		return $response->withBodyHTML($page);
	}

	public function deleteSpec(Request $request): Response
	{
		$response = new Response();
		$specId = $request->getPostParametersByName('specification-id');
		$categoryId = $request->getPostParametersByName('category-id');
		$this->specificationsService->deleteSpecificationById($specId);
		$specifications = $this->specificationsService->getSpecificationByCategoryId($categoryId);
		$page = $this->templateProcessor->render('delete-specification.php', [
			'specifications' => $specifications,
			'categoryId' => $categoryId,
			'isSpecificationDeleted' => true
		], 'layout/admin-main.php', []);
		return $response->withBodyHTML($page);
	}

}
