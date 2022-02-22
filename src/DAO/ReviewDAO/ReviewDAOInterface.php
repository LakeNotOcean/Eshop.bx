<?php

namespace Up\DAO\ReviewDAO;

use Up\Entity\Review;

interface ReviewDAOInterface
{
	public function save(Review $reviewDetail): Review;

	public function deleteById(int $id): void;

	/**
	 * @param int $itemId
	 * @param int $offset
	 * @param int $amount
	 *
	 * @return array<int,Review>
	 */
	public function getReviewsByItemId(int $itemId, int $offset, int $amount): array;

	/**
	 * @param int $userId
	 * @param int $offset
	 * @param int $amount
	 *
	 * @return array<int,Review>
	 */
	public function getReviewsByUserId(int $userId, int $offset, int $amount): array;

	public function existReviewByUserAndItemIds(int $userId, int $itemId): bool;
}