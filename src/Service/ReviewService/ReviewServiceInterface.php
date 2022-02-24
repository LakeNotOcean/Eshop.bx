<?php

namespace Up\Service\ReviewService;

use Up\Entity\Review;

interface ReviewServiceInterface
{
	public function save(Review $reviewDetail): Review;

	public function deleteById(int $id): void;

	/**
	 * @param array{offset:int, amount:int} $offsetCount
	 * @param int $itemId
	 *
	 * @return array<int,Review>
	 */
	public function getReviewsByItemId(array $offsetCount, int $itemId): array;

	/**
	 * @param array{offset:int, amount:int} $offsetCount
	 * @param int $userId
	 *
	 * @return array<int,Review>
	 */
	public function getReviewsByUserId(array $offsetCount, int $userId): array;

	public function existReviewByUserAndItemIds(int $userId, int $itemId): bool;
}