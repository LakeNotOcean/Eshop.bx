<?php

namespace Up\Service\ReviewService;

use Up\DAO\ReviewDAO\ReviewDAOInterface;
use Up\Entity\Review;

class ReviewService implements ReviewServiceInterface
{
	protected $reviewDAO;

	/**
	 * @param \Up\DAO\ReviewDAO\ReviewDAOmysql $reviewDAO
	 */
	public function __construct(ReviewDAOInterface $reviewDAO)
	{
		$this->reviewDAO = $reviewDAO;
	}

	public function save(Review $reviewDetail): Review
	{
		// TODO: Validate data
		return $this->reviewDAO->save($reviewDetail);
	}

	public function deleteById(int $id): void
	{
		$this->reviewDAO->deleteById($id);
	}

	/**
	 * @param array{offset:int, amountItems:int} $offsetCount
	 * @param int $itemId
	 *
	 * @return array<int,Review>
	 */
	public function getReviewsByItemId(array $offsetCount, int $itemId): array
	{
		return $this->reviewDAO->getReviewsByItemId($itemId, $offsetCount['offset'], $offsetCount['amountItems']);
	}

	/**
	 * @param array{offset:int, amountItems:int} $offsetCount
	 * @param int $userId
	 *
	 * @return array<int,Review>
	 */
	public function getReviewsByUserId(array $offsetCount, int $userId): array
	{
		return $this->reviewDAO->getReviewsByUserId($userId, $offsetCount['offset'], $offsetCount['amountItems']);
	}

	public function existReviewByUserAndItemIds(int $userId, int $itemId): bool
	{
		return $this->reviewDAO->existReviewByUserAndItemIds($userId, $itemId);
	}
}