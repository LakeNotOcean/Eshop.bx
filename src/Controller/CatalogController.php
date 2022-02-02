<?php

namespace Up\Controller;

use Up\Core\Message\Response;
use Up\Core\TemplateProcessor;
use Up\Service\CatalogService;


class CatalogController
{
	protected $templateProcessor;
	protected $catalogService;


	public static function getItems(): Response
	{
		$items = CatalogService::getItems();
		$pages = TemplateProcessor::Render('../src/layout/catalog.php',  ['items'=>$items], '../src/layout/main.php','');
		$response = new Response();
		$response = $response->withBodyHTML($pages);
		return $response;
	}

	public static function getResultCount(): int
	{
		return catalogService::getResultCount();
	}

}