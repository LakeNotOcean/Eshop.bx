<?php

namespace Up\Controller;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Service\ImageService\ImageServiceInterface;

class ImageController
{
	protected $templateProcessor;
	protected $imageService;

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\Service\ImageService\ImageService $imageService
	 */
	public function __construct(TemplateProcessorInterface     $templateProcessor,
								ImageServiceInterface          $imageService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->imageService = $imageService;
	}

	public function deleteImageById(Request $request, int $id): Response
	{
		$this->imageService->deleteImageById($id);
		return (new Response())->withBodyJSON([]);
	}
}