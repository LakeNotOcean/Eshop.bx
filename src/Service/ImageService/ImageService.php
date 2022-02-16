<?php

namespace Up\Service\ImageService;

use http\Exception;
use http\Exception\RuntimeException;
use Up\Core\Error\OSError;
use Up\Core\Settings\Settings;
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
		'image/png' => 'imagecreatefrompng',
		'image/webp' => 'imagecreatefromwebp',
	];
	private const validMimeTypeToSaveImageFunction = [
		'image/jpeg' => 'imagejpeg',
		'image/png' => 'imagepng',
		'image/webp' => 'imagewebp',
	];
	protected $imageDirPath;
	protected const OriginalImagesDir = 'original/';

	/**
	 * @var array<string, int>
	 */
	protected $imageDefaultSizes = [];

	public function __construct()
	{
		$settings = Settings::getInstance();
		$this->imageDirPath = $settings->getSettings('imageDirPath');
		$this->imageDefaultSizes['small'] = $settings->getSettings('smallImageSize');
		$this->imageDefaultSizes['medium'] = $settings->getSettings('mediumImageSize');
		$this->imageDefaultSizes['big'] = $settings->getSettings('bigImageSize');
	}

	/**
	 * @param array<array{name:string, type:string, tmp_name:string, error:int}> $imagesParams
	 * @param array<bool> $isMains
	 *
	 * @return array<ItemsImage>
	 * @throws MimeTypeException
	 */
	public function addImages(array $imagesParams, array $isMains): array
	{
		return array_map(
			function($imageParams, $isMain) {
				return $this->addImage($imageParams, $isMain);
			},
			$imagesParams,
			$isMains
		);
	}

	/**
	 * Загружает картинку в файл с картинками. Сохраняет в фаловой системе оригинальное изображение и его версии
	 * с измененными размерами
	 *
	 * @param array{name:string, type:string, tmp_name:string, error:int} $imageParams
	 * @param bool $isMain
	 *
	 * @return ItemsImage
	 * @throws MimeTypeException
	 */
	public function addImage(array $imageParams, bool $isMain): ItemsImage
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
		$sizedImagesPath = $this->createSizedImagesByOriginal($originalFilename, $mimeType);

		$itemImage = new ItemsImage();
		$itemImage->setIsMain($isMain);
		$itemImage->setOriginalImagePath($originalImagePath);
		foreach ($sizedImagesPath as $sizeName => $path)
		{
			$itemImage->setPath($sizeName, $path);
		}

		return $itemImage;
	}

	private function isValidImageMime(string $mimeType): bool
	{
		return array_key_exists($mimeType, $this::validMimeTypeToCreateImageFunction);
	}

	/**
	 * @param string $originalFilename
	 * @param string $originalFilenameMime
	 *
	 * @return array{small:string, medium:string, big:string}
	 * @throws MimeTypeException
	 */
	private function createSizedImagesByOriginal(string $originalFilename, string $originalFilenameMime): array
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

			$this->resizeImage($sizedImagePath, $sizeValue, $originalFilenameMime);
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
	private function resizeImage(string $filePath, int $size, string $mimeType): void
	{
		if (!isset(static::validMimeTypeToCreateImageFunction[$mimeType]))
		{
			throw new MimeTypeException("Invalid mime file type. Now: {$mimeType}");
		}

		$image = static::validMimeTypeToCreateImageFunction[$mimeType]($filePath);
		if (!$image)
		{
			throw new RuntimeException("Can't find file {$filePath}");
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
		static::validMimeTypeToSaveImageFunction[$mimeType]($image, $filePath);
	}
}
