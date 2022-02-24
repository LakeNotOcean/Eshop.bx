<?php

namespace Up\Controller;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Service\ImageService\ImageServiceInterface;

class ImageController
{
	protected $imageService;

	/**
	 * @param \Up\Service\ImageService\ImageService $imageService
	 */
	public function __construct(ImageServiceInterface $imageService)
	{
		$this->imageService = $imageService;
	}

	public function deleteImageById(Request $request, int $id): Response
	{
		$this->imageService->deleteImageById($id);
		return (new Response())->withBodyJSON([]);
	}

}