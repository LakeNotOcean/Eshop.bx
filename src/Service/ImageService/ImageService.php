<?php

namespace Up\Service\ImageService;

use http\Exception;
use http\Exception\RuntimeException;
use Up\Core\Error\OSError;
use Up\Core\Settings\Settings;
use Up\DAO\ImageDAO\ImageDAOInterface;
use Up\Entity\ItemDetail;
use Up\Entity\ItemsImage;
use Up\Lib\Mime\Error\MimeTypeException;
use Up\Lib\Mime\MimeMapper;


class ImageService implements ImageServiceInterface
{
	/**
	 * @var callable
	 */
	private const filenameHashFunc = 'md5';
	private const validMimeTypeToCreateImageFunction = [
		'image/jpeg' => 'imagecreatefromjpeg',
		'image/webp' => 'imagecreatefromwebp',
		'image/png' => 'imagecreatefrompng'
	];
	private const validMimeTypeToSaveImageFunction = [
		'image/jpeg' => 'imagejpeg',
		'image/webp' => 'imagewebp',
	];
	private const SIZED_IMAGE_FIRST_MIME = 'image/jpeg';
	protected $imageDirPath;
	protected $availableExtensions;
	protected const OriginalImagesDir = 'original/';

	/**
	 * @var array<string, int>
	 */
	protected $imageDefaultSizes = [];

	protected $imageDAO;
	/**
	 * @param \Up\DAO\ImageDAO\ImageDAOmysql $imageDAO
	 */
	public function __construct(ImageDAOInterface $imageDAO)
	{
		$this->imageDAO = $imageDAO;
		$settings = Settings::getInstance();
		$this->imageDirPath = $settings->getSettings('imageDirPath');
		$this->imageDefaultSizes['small'] = $settings->getSettings('smallImageSize');
		$this->imageDefaultSizes['medium'] = $settings->getSettings('mediumImageSize');
		$this->imageDefaultSizes['big'] = $settings->getSettings('bigImageSize');
		$this->availableExtensions = array_map(static function($mime)
		{
			return MimeMapper::getExtensionByMime($mime);
		}, array_keys($this::validMimeTypeToSaveImageFunction));
	}

	/**
	 * @param array<array{name:string, type:string, tmp_name:string, error:int, is_main:bool}> $imagesParams
	 *
	 * @return array<ItemsImage>
	 * @throws MimeTypeException
	 */
	public function addImages(array $imagesParams, ItemDetail $item): array
	{
		$images = array_map(
			function($imageParams) use ($item) {
				return $this->addImage($imageParams, $item);
			},
			$imagesParams,
		);
		return $this->imageDAO->saveAll($images, $item->getId());
	}

	/**
	 * Загружает картинку в файл с картинками. Сохраняет в фаловой системе оригинальное изображение и его версии
	 * с измененными размерами
	 *
	 * @param array{name:string, type:string, tmp_name:string, is_main:bool} $imageParams
	 * @param ItemDetail $item
	 *
	 * @return ItemsImage
	 * @throws MimeTypeException
	 */
	public function addImage(array $imageParams, ItemDetail $item): ItemsImage
	{
		$mimeType = $imageParams['type'];
		if (!$this->isValidImageMime($mimeType))
		{
			throw new MimeTypeException("Invalid mime file type. Now: {$mimeType}");
		}

		$this->createImageDirs();
		$originalFilename = $this->getUniqueFilename(
			$imageParams['name'],
			$mimeType
		);
		$originalImagePath = $this->getOriginalImagePathByFilename($originalFilename);

		$this->moveTmpFileToDirectory($imageParams['tmp_name'], $originalImagePath);
		$sizedImagePaths = $this->createSizedImagesByOriginal($originalFilename, $mimeType);

		foreach ($sizedImagePaths as $path)
		{
			$availableMimes  = array_keys($this::validMimeTypeToSaveImageFunction);

			foreach ($availableMimes as $availableMime)
			{
				$this->createImageWithExtension($path, MimeMapper::getExtensionByMime($availableMime), $availableMime);
			}
		}

		$itemImage = new ItemsImage();
		$itemImage->setIsMain($imageParams['is_main']);
		$itemImage->setOriginalImagePath($originalImagePath);

		foreach ($sizedImagePaths as $sizeName => $path)
		{
			$pathInfo = pathInfo($path);
			$imagePathWithoutExtension = $pathInfo['dirname'] . '/' . $pathInfo['filename'];
			$itemImage->setPath($sizeName, $imagePathWithoutExtension);
		}

		if($imageParams['is_main'] && $item->isSetMainImage())
		{
			$this->deleteImageFromFileSystem($item->getMainImage());
			$this->imageDAO->deleteById($item->getMainImage()->getId());
			$item->unsetMainImage();
		}

		return $itemImage;
	}

	private function isValidImageMime(string $mimeType): bool
	{
		return array_key_exists($mimeType, $this::validMimeTypeToCreateImageFunction);
	}

	/**
	 * @param string $originalFilename
	 *
	 * @return array{small:string, medium:string, big:string}
	 * @throws MimeTypeException
	 */
	private function createSizedImagesByOriginal(string $originalFilename): array
	{
		$originalFilePath = $this->getOriginalImagePathByFilename($originalFilename);
		$sizedImagePaths = [];

		foreach ($this->imageDefaultSizes as $sizeName => $sizeValue)
		{
			$sizedImagePath = $this->getSizedImagePath($originalFilename, $sizeName);
			if (!copy($originalFilePath, $sizedImagePath))
			{
				throw new OSError("Can't copy file from {$originalFilePath} to {$sizedImagePath}");
			}
			[$width, $height] = getimagesize($sizedImagePath);

			if ($width > $sizeValue || $height > $sizeValue)
			{
				// TODO отрефактори этот момент. Продумай названия переменных и т.д.
				$this->resizeImage($sizedImagePath, $sizeValue, mime_content_type($originalFilePath), static::SIZED_IMAGE_FIRST_MIME);
			}
			$sizedImagePaths[$sizeName] = $sizedImagePath;
		}

		return $sizedImagePaths;
	}

	/**
	 * @return void
	 * @throws OSError
	 */
	private function createImageDirs(): void
	{
		$originalFilePath = $this->imageDirPath . $this::OriginalImagesDir;
		if (
			!is_dir($originalFilePath)
			&& !mkdir(
				$originalFilePath,
				0777,
				true
			)
			&& !is_dir($originalFilePath)
		)
		{
			throw new OSError(sprintf('Directory "%s" was not created', $originalFilePath));
		}
		foreach (array_keys($this->imageDefaultSizes) as $dirForSizedImages)
		{
			if (is_dir($this->imageDirPath . $dirForSizedImages))
			{
				continue;
			}
			if (
				!mkdir($this->imageDirPath . $dirForSizedImages, 0777, true)
				&& !is_dir($this->imageDirPath . $dirForSizedImages)
			)
			{
				throw new OSError(sprintf('Directory "%s" was not created', $this->imageDirPath . $dirForSizedImages));
			}

		}
	}

	private function getOriginalImagePathByFilename(string $filename): string
	{
		return $this->imageDirPath . $this::OriginalImagesDir . $filename;
	}

	/**
	 * @param string $filename
	 * @param string $mimeType
	 *
	 * @return string
	 */
	private function getUniqueFilename(string $filename, string $mimeType): string
	{
		$fileExtension = MimeMapper::getExtensionByMime($mimeType);
		$hash = ($this::filenameHashFunc)($filename);
		$newFilename = $this->generateFilename($hash, $fileExtension);

		if (!$this->originalImageInDirectoryExist($newFilename))
		{
			return $newFilename;
		}

		$hashPostfixCounter = 1;
		$resultFilename = $this->generateFilename(
			$hash . (string)$hashPostfixCounter,
			$fileExtension,
		);
		while ($this->originalImageInDirectoryExist($resultFilename))
		{
			$hashPostfixCounter++;
			$resultFilename = $this->generateFilename(
				$hash . (string)$hashPostfixCounter,
				$fileExtension,
			);
		}

		return $resultFilename;
	}

	public function deleteImageById(int $imageId): void
	{
		$image = $this->imageDAO->getImageById($imageId);
		$this->deleteImageFromFileSystem($image);
		$this->imageDAO->deleteById($imageId);
	}

	public function deleteImagesByItemId(int $itemId): void
	{
		$images = $this->imageDAO->getImagesByItemId($itemId);
		foreach ($images as $image)
		{
			$this->deleteImageFromFileSystem($image);
		}
		$this->imageDAO->deleteImagesByItemId($itemId);
	}

	private function generateFilename(string $imageFilename, string $fileExtension): string
	{
		return $imageFilename . '.' . $fileExtension;
	}

	private function originalImageInDirectoryExist(string $imageFilename): bool
	{
		return file_exists($this->imageDirPath . $this::OriginalImagesDir . $imageFilename);
	}

	private function getSizedImagePath(string $filename, string $size): string
	{
		return $this->imageDirPath . $size . '/' . $filename;
	}

	private function moveTmpFileToDirectory(string $tmpFilePath, string $resultPath): void
	{
		if (!move_uploaded_file($tmpFilePath, $resultPath))
		{
			throw new OSError("Can't upload file {$tmpFilePath} to {$resultPath}");
		}
	}

	/**
	 * @throws MimeTypeException
	 */
	private function resizeImage(string $filePath, int $size, string $originalFileMimeType, string $resultFileMimeType): void
	{
		if (!isset(static::validMimeTypeToCreateImageFunction[$originalFileMimeType]))
		{
			throw new MimeTypeException("Invalid mime file type. Now: {$originalFileMimeType}");
		}

		$image = static::validMimeTypeToCreateImageFunction[$originalFileMimeType]($filePath);
		if (!$image)
		{
			throw new \RuntimeException("Can't find file {$filePath}");
		}

		$width = imagesx($image);
		$height = imagesy($image);
		if ($height >= $width)
		{
			$image = imagescale($image, floor($size * $width / $height), $size);
		}
		else
		{
			$image = imagescale($image, $size, floor($size * $height / $width));
		}
		static::validMimeTypeToSaveImageFunction[$resultFileMimeType]($image, $filePath);
	}

	public function createImageWithExtension(string $imagePath, string $anotherExtension, string $resultFileMime): string
	{
		$createImageFunc = static::validMimeTypeToCreateImageFunction[
			mime_content_type($imagePath)
		];
		$image = $createImageFunc($imagePath);

		$pathInfo = pathinfo($imagePath);
		$imagePathWithoutExtension = $pathInfo['dirname'] . '/' . $pathInfo['filename'];

		$resultPath = $imagePathWithoutExtension . '.' . $anotherExtension;
		static::validMimeTypeToSaveImageFunction[
		$resultFileMime
			]($image, $resultPath);

		return $resultPath;
	}

	private function deleteImageFromFileSystem(ItemsImage $image)
	{
		unlink($image->getOriginalImagePath());
		foreach ($image->getPathArray() as $sizedPath)
		{
			foreach ($this->availableExtensions as $availableExtension)
			{
				unlink($this->generateFilename($sizedPath, $availableExtension));
			}
		}
	}
}
