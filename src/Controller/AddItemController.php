<?php

namespace Up\Controller;

use Up\Core\Message\Error\NoSuchQueryParameterException;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessor;
use Up\Entity\EntityArray;
use Up\Entity\ItemDetail;
use Up\Entity\ItemType;
use Up\Entity\Specification;
use Up\Entity\SpecificationCategory;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\SpecificationService\SpecificationsService;
use Up\Service\TagService\TagService;

class AddItemController
{
	protected $templateProcessor;
	protected $specificationsService;
	protected $tagService;
	protected $itemService;

	public function __construct(TemplateProcessor $templateProcessor,
								SpecificationsService $specificationsService,
								TagService $tagService,
								ItemServiceInterface $itemService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->specificationsService = $specificationsService;
		$this->tagService = $tagService;
		$this->itemService = $itemService;
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

	/**
	 * @throws NoSuchQueryParameterException
	 */
	public function test(Request $request): Response
	{
		$item = new ItemDetail();
		$item->setTitle($request->getPostParametersByName('item-title'));
		$item->setPrice($request->getPostParametersByName('item-price'));
		$item->setShortDescription($request->getPostParametersByName('item-short-description'));
		$item->setFullDescription($request->getPostParametersByName('item-full-description'));
		$item->setIsActive(true);
		$item->setSortOrder(3);
		$tagsString = $request->getPostParametersByName('item-tags');
		$tags = $this->tagService->save(array_map('trim', explode(',', $tagsString)));
		$item->setTags($tags);
		$categoriesArray = $request->getPostParametersByName('specs');
		$categories = new EntityArray();
		foreach ($categoriesArray as $idCat => $categoryArray)
		{
			$category = new SpecificationCategory($idCat);
			foreach ($categoryArray as $idSpec => $specValue)
			{
				$specification = new Specification($idSpec);
				$specification->setValue($specValue);
				$category->addToSpecificationList($specification);
			}
			$categories->addEntity($category);
		}
		$item->setItemType(new ItemType(1, 'Видеокарта'));
		$item->setSpecificationCategoryList($categories);
		$this->itemService->save($item);
		return (new Response())->withBodyHTML('');
	}
}
