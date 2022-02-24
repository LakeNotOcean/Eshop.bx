<?php

namespace Up\Controller;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\Review;
use Up\LayoutManager\MainLayoutManager;
use Up\Lib\Paginator\Paginator;
use Up\Lib\Redirect;
use Up\Service\ItemService\ItemService;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\ReviewService\ReviewServiceInterface;

class ReviewController
{
	protected $templateProcessor;
	protected $reviewService;
	protected $itemService;
	protected $mainLayoutManager;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\Service\ReviewService\ReviewService $reviewService
	 * @param \Up\Service\ItemService\ItemService $itemService
	 * @param \Up\LayoutManager\MainLayoutManager $mainLayoutManager
	 */
	public function __construct(TemplateProcessorInterface $templateProcessor,
								ReviewServiceInterface     $reviewService,
								ItemServiceInterface       $itemService,
								MainLayoutManager          $mainLayoutManager)
	{
		$this->templateProcessor = $templateProcessor;
		$this->reviewService = $reviewService;
		$this->itemService = $itemService;
		$this->mainLayoutManager = $mainLayoutManager;
	}

	public function saveReview(Request $request): Response
	{
		$itemId = $request->getPostParametersByName('item_id');
		$text = $request->getPostParametersByName('text_review');
		$rating = $request->getPostParametersByName('rating');
		$item = $this->itemService->getItemById($itemId);
		$review = new Review();
		$review->setRating($rating);
		$review->setComment($text);
		$review->setUser($request->getUser());
		$review->setItem($item);
		$review->setDate(new \DateTime());
		$this->reviewService->save($review);
		return Redirect::createResponseByURLName('item-detail', [], ['id' => $itemId]);
	}

	public function deleteReview(Request $request, int $id): Response
	{
		$this->reviewService->deleteById($id, $request->getUser());
		return (new Response())->withBodyHTML('');
	}

	public function test(Request $request): Response
	{
		var_dump($_POST);
		return (new Response())->withBodyHTML('');
	}
}