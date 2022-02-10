<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessor;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;
use Up\Service\SpecificationService\SpecificationsService;
use Up\Service\UserService\UserService;

class AddItemController
{
	protected $templateProcessor;
	protected $specificationsService;

	public function __construct(TemplateProcessor $templateProcessor, SpecificationsService $specificationsService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->specificationsService = $specificationsService;
	}

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function addItem(Request $request): Response
	{
		$itemTypes = $this->specificationsService->getItemTypes();
		$categories = $this->specificationsService->getCategoriesWithSpecifications();
		$template = $this->specificationsService->getItemTemplate((int)$request->getQueriesByName('item-type'));

		$page = $this->templateProcessor->render('add-item.php', [
			'itemTypes' => $itemTypes,
			'categories' => $categories,
			'template' => $template
		], 'admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	public function chooseItemType(Request $request): Response
	{
		$itemTypes = $this->specificationsService->getItemTypes();

		$page = $this->templateProcessor->render('choose-item-type.php', [
			'itemTypes' => $itemTypes
		], 'admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	public function addItemType(Request $request): Response
	{
		$page = $this->templateProcessor->render('add-item-type.php', [

		], 'admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	public function addCategory(Request $request): Response
	{
		$page = $this->templateProcessor->render('add-category.php', [
			'isNewCategoryAdded' => false
		], 'admin-main.php', []);

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
		], 'admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

	public function addSpecification(Request $request): Response
	{
		$categories = $this->specificationsService->getCategories();
		$page = $this->templateProcessor->render('add-specification.php', [
			'categories' => $categories,
			'isNewSpecAdded' => false
		], 'admin-main.php', []);

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
		], 'admin-main.php', []);

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

	public function getSpecsByCategoryIdJSON(Request $request, int $id): Response
	{
		$response = new Response();
		$response = $response->withBodyJSON(array_map(function(Specification $spec){
			return $spec->getName();
		}, $this->specificationsService->getSpecificationByCategoryId($id)));
		return $response;
	}

	public function getCategoriesWithSpecsJSON(Request $request): Response
	{
		$response = new Response();
		$response = $response->withBodyJSON(array_map(function(SpecificationCategory $cat){
			return array_map(function(Specification $spec){
				return $spec->getName();
			}, $cat->getSpecificationList()->getEntitiesArray());
		}, $this->specificationsService->getCategoriesWithSpecifications()));
		return $response;
	}

	public function test(Request $request): Response
	{
		var_dump($_POST);
		return (new Response())->withBodyHTML('');
	}
}
