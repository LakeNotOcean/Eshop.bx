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
			'up_original_image',
			[
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

	private function prepareSaveImageWithSizeQuery(int $countOfInsertedValues): PDOStatement
	{
		return $this->getInsertPrepareStatement(
			'up_image_with_size',
			[
				'ORIGINAL_IMAGE_ID',
				'PATH',
				'SIZE',
			],
			$countOfInsertedValues
		);
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
				$result[] = $image->getPathWithoutExtension($size);
				$result[] = $size;
			}
		}

		return $result;
	}

	public function getImageById(int $id): ItemsImage
	{
		$query = $this->prepareGetImagesByOriginalImageIdQuery();
		$query->bindValue(1, $id, $this->dbConnection::PARAM_INT);
		$query->execute();

		$image = new ItemsImage();
		$row = $query->fetch($this->dbConnection::FETCH_ASSOC);
		$image
			->setId($row['ID'])->setIsMain($row['IS_MAIN'])
			->setOriginalImagePath($row['ORIGINAL_PATH'])->setPath($row['SIZE'],$row['PATH']);

		while ($row = $query->fetch($this->dbConnection::FETCH_ASSOC))
		{
			$image->setPath($row['SIZE'],$row['PATH']);
		}

		return $image;
	}

	private function prepareGetImagesByOriginalImageIdQuery(): PDOStatement
	{
		$query = "SELECT uoi.ID, uoi.PATH ORIGINAL_PATH, uoi.IS_MAIN, uiws.PATH, uiws.SIZE
				  FROM up_original_image uoi
				  INNER JOIN up_image_with_size uiws on uoi.ID = uiws.ORIGINAL_IMAGE_ID
				  WHERE uoi.ID = ?";

		return $this->dbConnection->prepare($query);
	}

	public function deleteImagesByItemId(int $imageId): void
	{
		$statement = $this->getDeleteByItemIdPrepareStatement();
		$statement->bindValue(1, $imageId, $this->dbConnection::PARAM_INT);
		$statement->execute($imageId);
	}

	private function getDeleteByItemIdPrepareStatement(): PDOStatement
	{
		$query = "DELETE FROM up_original_image WHERE ITEM_ID=?";

		return $this->dbConnection->prepare($query);
	}

	public function deleteById(int $imageId): void
	{
		$query = "DELETE FROM up_original_image WHERE ID=?";
		$statement = $this->dbConnection->prepare($query);
		$statement->execute([$imageId]);
	}

	/**
	 * @param array<ItemsImage> $images
	 * @param int $itemId
	 *
	 * @return array<ItemsImage>
	 */
	public function saveAll(array $images, int $itemId): array
	{
		try
		{
			$this->dbConnection->beginTransaction();
			$insertOriginal = $this->getInsertPrepareStatement(
				'up_original_image',
				['PATH', 'ITEM_ID', 'IS_MAIN'],
				count($images)
			);
			$insertOriginalArray = [];
			foreach ($images as $image)
			{
				$insertOriginalArray[] = $image->getOriginalImagePath();
				$insertOriginalArray[] = $itemId;
				$insertOriginalArray[] = $image->isMain();
				if ($image->isMain())
				{
					$this->deleteMainImageByItemId($itemId);
				}
			}
			$insertOriginal->execute($insertOriginalArray);
			$this->getInsertedOriginal($images, $itemId);
			$insertSizeArray = [];
			$countInsertSize = 0;
			foreach ($images as $image)
			{
				$withSize = $image->getPathArray();
				$countInsertSize += count($withSize);
				foreach ($withSize as $size => $path)
				{
					$insertSizeArray[] = $image->getId();
					$insertSizeArray[] = $path;
					$insertSizeArray[] = $size;
				}
			}

			$insertSize = $this->getInsertPrepareStatement(
				'up_image_with_size',
				['ORIGINAL_IMAGE_ID', 'PATH', 'SIZE'],
				$countInsertSize
			);
			$insertSize->execute($insertSizeArray);
			$this->dbConnection->commit();

			return $this->getImagesByItemId($itemId);
		}
		catch (PDOException $exception)
		{
			$this->dbConnection->rollBack();
			throw $exception;
		}
	}

	public function deleteMainImageByItemId(int $itemId): void
	{
		$query = "DELETE FROM up_original_image WHERE ITEM_ID=? AND IS_MAIN=1";
		$statement = $this->dbConnection->prepare($query);
		$statement->execute([$itemId]);
	}

	/**
	 * @param array<ItemsImage> $images
	 * @param int $itemId
	 */
	private function getInsertedOriginal(array $images, int $itemId): void
	{
		$result = $this->getSelectPrepareStatement('up_original_image', ['ITEM_ID' => '=']);
		$result->execute(['ITEM_ID' => $itemId]);
		while ($row = $result->fetch())
		{
			foreach ($images as $image)
			{
				if ($image->getOriginalImagePath() === $row['PATH'])
				{
					$image->setId($row['ID']);
				}
			}
		}
	}

	/**
	 * @param int $itemId
	 *
	 * @return array<ItemsImage>
	 */
	public function getImagesByItemId(int $itemId): array
	{
		$result = $this->prepareGetImagesByItemId();
		$result->execute([$itemId]);
		$images = [];
		while ($row = $result->fetch())
		{
			if (!array_key_exists($row['ID'], $images))
			{
				$images[$row['ID']] = $this->getItemsImageByRow($row);
			}
			if (!$images[$row['ID']]->hasSize($row['SIZE']))
			{
				$images[$row['ID']]->setPath($row['SIZE'], $row['PATH']);
			}
		}

		return $images;
	}

	private function prepareGetImagesByItemId(): PDOStatement
	{
		$query = "SELECT uoi.ID, uoi.PATH ORIGINAL_PATH, uoi.IS_MAIN, uiws.PATH, uiws.SIZE
				  FROM up_original_image uoi
				  INNER JOIN up_image_with_size uiws on uoi.ID = uiws.ORIGINAL_IMAGE_ID AND uoi.ITEM_ID=?";

		return $this->dbConnection->prepare($query);
	}

	private function getItemsImageByRow(array $row): ItemsImage
	{
		$image = new ItemsImage();
		$image->setId($row['ID']);
		$image->setIsMain($row['IS_MAIN']);
		$image->setOriginalImagePath($row['ORIGINAL_PATH']);

		return $image;
	}
}