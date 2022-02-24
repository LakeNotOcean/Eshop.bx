<?php

namespace Up\DAO\ReviewDAO;

use Up\Entity\Review;

interface ReviewDAOInterface
{
	public function save(Review $reviewDetail): Review;

	public function deleteById(int $id): void;

	/**
	 * @param int $userId
	 * @param array<int> $itemIds
	 *
	 * @return array<int,Review>
	 */
	public function getUsersReviewsByItemIds(int $userId, array $itemIds): array;

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

	public function getAmountReviewsByUserId(int $userId): int;

	public function getReviewById(int $id): Review;
}