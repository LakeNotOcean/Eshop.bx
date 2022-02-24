<?php

namespace Up\Service\ReviewService;

use Up\Entity\Review;
use Up\Entity\User\User;

interface ReviewServiceInterface
{
	public function save(Review $reviewDetail): Review;

	public function deleteById(int $id, User $user): void;

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

	/**
	 * @param int $userId
	 * @param array $itemIds
	 *
	 * @return array<int,Review> массив, ключами которого являются id товаров(а не отзывов!!!)
	 */
	public function getUsersReviewsByItemIds(int $userId, array $itemIds): array;

	public function existReviewByUserAndItemIds(int $userId, int $itemId): bool;
}