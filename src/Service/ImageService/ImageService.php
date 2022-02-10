<?php

namespace Up\Service\ImageService;

use http\Exception\RuntimeException;
use Up\Core\Error\OSError;
use Up\Core\Settings\Settings;
use Up\DAO\ImageDAO\ImageDAOInterface;
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
	 * @param bool[] $isMains
	 *
	 * @return ItemsImage[]
	 * @throws MimeTypeException
	 */
	public function addImages(array $imagesParams, array $isMains): array
	{
		return array_map(
			function($imageParams, $isMain)
			{
				return $this->addImage($imageParams, $isMain);
			},
			$imagesParams,
			$isMains
		);
	}

	/**
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

		$this->createImageDir();
		$filename = $this->getUniqueFilename(
			$imageParams['name'],
			$this->imageDefaultSizes['big'],
			$mimeType
		);
		$this->moveTmpFileToDirectory($imageParams['tmp_name'], $filename);
		$this->resizeImage($filename, $this->imageDefaultSizes['big'], $mimeType);

		$itemImage = new ItemsImage();
		$itemImage->setIsMain($isMain);
		$itemImage->setPath($this->getPathByFilename($filename));

		return $itemImage;
	}

	private function isValidImageMime(string $mimeType): bool
	{
		return array_key_exists($mimeType, $this::validMimeTypeToCreateImageFunction);
	}

	/**
	 * @param bool $exist_ok
	 *
	 * @return void
	 * @throws OSError
	 */
	private function createImageDir(bool $exist_ok = true): void
	{
		if (is_dir($this->imageDirPath))
		{
			if (!$exist_ok)
			{
				throw new OSError("Directory {$this->imageDirPath} already exist");
			}

			return;
		}

		if (!mkdir($this->imageDirPath) && !is_dir($this->imageDirPath))
		{
			throw new OSError(sprintf('Directory "%s" was not created', $this->imageDirPath));
		}
	}

	/**
	 * @param string $imageFilename
	 * @param int $sizePostfix
	 * @param string $mimeType
	 *
	 * @return string
	 */
	private function getUniqueFilename(string $imageFilename, int $sizePostfix, string $mimeType): string
	{
		$fileExtension = MimeMapper::getExtensionByMime($mimeType);
		$hash = ($this::filenameHashFunc)($imageFilename);
		$filename = $this->generateFilename($hash, $sizePostfix, $fileExtension);

		if (!$this->imageInDirectoryExist($filename))
		{
			return $filename;
		}

		$hashPostfixCounter = 1;
		$resultFilename = $this->generateFilename(
			$hash . '-' . (string)$hashPostfixCounter,
			$sizePostfix,
			$fileExtension
		);
		while ($this->imageInDirectoryExist($resultFilename))
		{
			$hashPostfixCounter++;
			$resultFilename = $this->generateFilename(
				$hash . '-' . (string)$hashPostfixCounter,
				$sizePostfix,
				$fileExtension
			);
		}

		return $resultFilename;
	}

	private function generateFilename(string $imageFilename, int $sizePostfix, string $fileExtension): string
	{
		return $imageFilename . '_' . (string)$sizePostfix . '.' . $fileExtension;
	}

	private function imageInDirectoryExist(string $imageFilename): bool
	{
		return file_exists($this->getPathByFilename($imageFilename));
	}

	private function getPathByFilename(string $filename)
	{
		return $this->imageDirPath . $filename;
	}

	private function moveTmpFileToDirectory(string $tmpFilePath, string $resultFileName): void
	{
		move_uploaded_file($tmpFilePath, $this->getPathByFilename($resultFileName));
	}

	/**
	 * @throws MimeTypeException
	 */
	private function resizeImage(string $filename, int $size, string $mimeType): void
	{
		if (!isset(static::validMimeTypeToCreateImageFunction[$mimeType]))
		{
			throw new MimeTypeException("Invalid mime file type. Now: {$mimeType}");
		}
		$filePath = $this->getPathByFilename($filename);
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