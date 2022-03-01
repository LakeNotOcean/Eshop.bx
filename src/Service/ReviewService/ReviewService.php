<?php

namespace Up\Service\ReviewService;

use Up\DAO\ReviewDAO\ReviewDAOInterface;
use Up\Entity\Review;
use Up\Entity\User\User;
use Up\Entity\User\UserEnum;
use Up\Service\Error\ForbiddenException;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\OrderService\OrderServiceInterface;
use Up\Service\ReviewService\Error\ReviewException;
use Up\Validator\DataTypes;
use Up\Validator\ValidationException;
use Up\Validator\Validator;

class ReviewService implements ReviewServiceInterface
{
	protected $reviewDAO;
	protected $orderService;
	protected $itemService;

	/**
	 * @param \Up\DAO\ReviewDAO\ReviewDAOmysql $reviewDAO
	 * @param \Up\Service\OrderService\OrderService $orderService
	 * @param \Up\Service\ItemService\ItemService $itemService
	 */
	public function __construct(ReviewDAOInterface $reviewDAO, OrderServiceInterface $orderService, ItemServiceInterface $itemService)
	{
		$this->reviewDAO = $reviewDAO;
		$this->orderService = $orderService;
		$this->itemService = $itemService;
	}

	/**
	 * @throws ValidationException
	 * @throws ReviewException
	 */
	public function save(Review $reviewDetail): Review
	{
		$errors = Validator::validateFields([
												'rating' => [$reviewDetail->getRating(), DataTypes::rating(), "Ошибка в оценке: "],
												'text_review' => [$reviewDetail->getComment(), DataTypes::reviewText(), "Ошибка в тексте отзыва: "],
											]);
		if(!empty($errors))
		{
			throw new ValidationException('Validation failed', $errors);
		}
		$userId = $reviewDetail->getUser()->getId();
		$itemId = $reviewDetail->getItem()->getId();
		$itemIsPurchased = $this->orderService->checkThatUserBoughtItem($userId, $itemId);
		$reviewIsWritten = $this->existReviewByUserAndItemIds($userId, $itemId);

		if (!$itemIsPurchased || $reviewIsWritten)
		{
			throw new ReviewException('Failed to buy', ['item' => ['Вы еще не купили товар или уже оставляли отзыв']]);
		}

		return $this->reviewDAO->save($reviewDetail);
	}

	/**
	 * @throws ForbiddenException
	 */
	public function deleteById(int $id, User $user): void
	{
		$review = $this->reviewDAO->getReviewById($id);
		if ($review->getUser()->getId() !== $user->getId() && $user->getRole()->getName() != UserEnum::Admin())
		{
			throw new ForbiddenException('Not allow');
		}
		$this->reviewDAO->deleteById($id);
	}

	public function getAmountReviewsByUserId(int $userId): int
	{
		return $this->reviewDAO->getAmountReviewsByUserId($userId);
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
		$reviews = $this->reviewDAO->getReviewsByUserId($userId, $offsetCount['offset'], $offsetCount['amountItems']);
		$this->changeItemsToUserItems($reviews, $userId);
		return $reviews;
	}

	public function getUsersReviewsByItemIds(int $userId, array $itemIds): array
	{
		$reviews = $this->reviewDAO->getUsersReviewsByItemIds($userId, $itemIds);
		$reviewsWithChangedKeys = [];
		foreach ($reviews as $review)
		{
			$reviewsWithChangedKeys[$review->getItem()->getId()] = $review;
		}
		return $reviewsWithChangedKeys;
	}

	public function existReviewByUserAndItemIds(int $userId, int $itemId): bool
	{
		return $this->reviewDAO->existReviewByUserAndItemIds($userId, $itemId);
	}

	private function changeItemsToUserItems(array $reviews, int $userId): void
	{
		$items = array_map(function(Review $review){return $review->getItem();}, $reviews);
		$userItems = $this->itemService->mapItemsToUserItems($userId, $items);
		foreach ($reviews as $review)
		{
			$review->setItem($userItems[$review->getItem()->getId()]);
		}
	}
}