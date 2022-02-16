<?php

namespace Up\DAO\ImageDAO;

use PDOException;
use PDOStatement;
use Up\Core\Database\DefaultDatabase;
use Up\DAO\AbstractDAO;
use Up\Entity\ItemsImage;

class ImageDAOmysql extends AbstractDAO implements ImageDAOInterface
{
	/**
	 * @param \Up\Core\Database\DefaultDatabase $dbConnection
	 */
	public function __construct(DefaultDatabase $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function save(ItemsImage $image, int $itemId): void
	{
		try
		{
			$this->dbConnection->beginTransaction();
			$originalImagePreparedQuery = $this->prepareSaveOriginalImageQuery(1);
			$originalImagePreparedQuery->execute($this->getSaveOriginalImageQueryExecutionArray([$image], [$itemId]));
			$originalImageId = (int)$this->dbConnection->lastInsertId();

			$sizeImagePrepareQuery = $this->prepareSaveImageWithSizeQuery(3);
			$sizeImagePrepareQuery->execute(
				$this->getSaveImageWithSizeQueryExecutionArray([$image], [$originalImageId])
			);

			$this->dbConnection->commit();
		}
		catch (PDOException $exception)
		{
			$this->dbConnection->rollBack();
			throw $exception;
		}
	}

	private function prepareSaveOriginalImageQuery(int $countOfInsertedValues): PDOStatement
	{
		return $this->getInsertPrepareStatement(
			'up_original_image', [
								   'PATH',
								   'ITEM_ID',
								   'IS_MAIN',
							   ],
			$countOfInsertedValues
		);
	}

	/**
	 * @param array<ItemsImage> $images
	 * @param array<int> $itemIds
	 *
	 * @return array
	 */
	private function getSaveOriginalImageQueryExecutionArray(array $images, array $itemIds): array
	{
		$result = [];

		for ($imageIndex = 0, $imageIndexMax = count($images); $imageIndex < $imageIndexMax; $imageIndex++)
		{
			$image = $images[$imageIndex];
			$result[] = $image->getOriginalImagePath();
			$result[] = $itemIds[$imageIndex];
			$result[] = $image->isMain();
		}

		return $result;
	}

	/**
	 * @param array<ItemsImage> $images
	 * @param array<int> $originalImageIds
	 *
	 * @return array
	 */
	private function getSaveImageWithSizeQueryExecutionArray(array $images, array $originalImageIds): array
	{
		$result = [];

		for ($imageIndex = 0, $imageIndexMax = count($images); $imageIndex < $imageIndexMax; $imageIndex++)
		{
			$image = $images[$imageIndex];
			foreach ($image->getSizes() as $size)
			{
				$result[] = $originalImageIds[$imageIndex];
				$result[] = $image->getPath($size);
				$result[] = $size;
			}
		}

		return $result;
	}

	private function prepareSaveImageWithSizeQuery(int $countOfInsertedValues): PDOStatement
	{
		return $this->getInsertPrepareStatement(
			'up_image_with_size', [
									'ORIGINAL_IMAGE_ID',
									'PATH',
									'SIZE',
								],
			$countOfInsertedValues
		);
	}
}