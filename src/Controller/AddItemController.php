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
		$template = $this->specificationsService->getItemTemplate(intval('$request->getQueriesByName()'));


		$page = $this->templateProcessor->render('add-item.php', [
			'itemTypes' => $itemTypes,
			'categories' => $categories,
			'template' => $template
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
