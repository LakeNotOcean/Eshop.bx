<?php

namespace Up\Service\ReviewService;

use Up\Core\Error\ValidationException;
use Up\DAO\ReviewDAO\ReviewDAOInterface;
use Up\Entity\Review;
use Up\Service\OrderService\OrderServiceInterface;
use Up\Service\ReviewService\Error\ReviewException;
use Up\Validator\DataTypes;
use Up\Validator\Validator;

class ReviewService implements ReviewServiceInterface
{
	protected $reviewDAO;
	protected $orderService;

	/**
	 * @param \Up\DAO\ReviewDAO\ReviewDAOmysql $reviewDAO
	 * @param \Up\Service\OrderService\OrderService $orderService
	 */
	public function __construct(ReviewDAOInterface $reviewDAO, OrderServiceInterface $orderService)
	{
		$this->reviewDAO = $reviewDAO;
		$this->orderService = $orderService;
	}

	/**
	 * @throws ValidationException
	 * @throws ReviewException
	 */
	public function save(Review $reviewDetail): Review
	{
		$error = Validator::validate($reviewDetail->getRating(), DataTypes::rating());
		$error .= Validator::validate($reviewDetail->getComment(), DataTypes::reviewText());
		if($error !== '')
		{
			throw new ValidationException($error);
		}
		$userId = $reviewDetail->getUser()->getId();
		$itemId = $reviewDetail->getItem()->getId();
		$itemIsPurchased = $this->orderService->checkThatUserBoughtItem($userId, $itemId);
		$reviewIsWritten = $this->existReviewByUserAndItemIds($userId, $itemId);

		if (!$itemIsPurchased || $reviewIsWritten)
		{
			throw new ReviewException('Вы еще не купили товар или уже писали отзыв на этот товар');
		}

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