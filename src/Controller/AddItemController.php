<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessor;
use Up\Service\SpecificationService\SpecificationsService;

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
		$categories = $this->specificationsService->getCategories();
		$specs = $this->specificationsService->getSpecifications();
		$template = $this->specificationsService->getItemTemplate('$request->getQueriesByName()');

		$page = $this->templateProcessor->render('add-item.php', [
			'itemTypes' => $itemTypes,
			'categories' => $categories,
			'specs' => $specs,
			'template' => $template
		], 'admin-main.php', []);

		$response = new Response();

		return $response->withBodyHTML($page);
	}

}
