<?php

namespace Up\DAO\ReviewDAO;

use Up\Core\Database\DefaultDatabase;
use Up\DAO\AbstractDAO;
use Up\Entity\Item;
use Up\Entity\ItemsImage;
use Up\Entity\Review;
use Up\Entity\User\User;
use Up\Entity\User\UserEnum;
use Up\Entity\User\UserRole;

class ReviewDAOmysql extends AbstractDAO implements ReviewDAOInterface
{

	/**
	 * @param \Up\Core\Database\DefaultDatabase $dbConnection
	 */
	public function __construct(DefaultDatabase $dbConnection)
	{
		$this->dbConnection = $dbConnection;
	}

	public function save(Review $reviewDetail): Review
	{
		$this->getInsertPrepareStatement('up_review', ['USER_ID', 'ITEM_ID', 'SCORE', 'COMMENT', 'DATE_CREATE'])
			->execute([$reviewDetail->getUser()->getId(), $reviewDetail->getItem()->getId(), $reviewDetail->getRating(), $reviewDetail->getComment(), $reviewDetail->getDate()->format('Y-m-d')]);
		$id = $this->dbConnection->lastInsertId();
		$reviewDetail->setId($id);
		return $reviewDetail;
	}

	public function deleteById(int $id): void
	{
		$this->dbConnection->query("DELETE FROM up_review WHERE ID={$id}");
	}

	public function getReviewsByItemId(int $itemId, int $offset, int $amount): array
	{
		$statement = $this->getSelectByItemIdStatement($offset, $amount);
		$statement->execute([$itemId]);
		return $this->mapReviews($statement);
	}

	public function getReviewsByUserId(int $userId, int $offset, int $amount): array
	{
		$statement = $this->getSelectByUserIdStatement($offset, $amount);
		$statement->execute([$userId]);
		return $this->mapReviews($statement);
	}

	public function existReviewByUserAndItemIds(int $userId, int $itemId): bool
	{
		$statement = $this->dbConnection->prepare("SELECT 1 FROM up_review WHERE USER_ID=? AND ITEM_ID=? LIMIT 1");
		$statement->execute([$userId, $itemId]);
		return (bool)$statement->fetch();
	}

	/**
	 * @return array<int,Review>
	 */
	private function mapReviews(\PDOStatement $statement): array
	{
		/** @var array<int,Review> $reviews */
		$reviews = [];
		while ($row = $statement->fetch())
		{
			if(!array_key_exists($row['r_id'], $reviews))
			{
				$reviews[$row['r_id']] = $this->mapReviewCommonInfo($row);
			}
			$review = $reviews[$row['r_id']];
			if(!$review->issetItem())
			{
				$review->setItem($this->mapItemCommonInfo($row));
			}
			if(!$review->getItem()->isSetMainImage())
			{
				$review->getItem()->setMainImage($this->mapImageCommonInfo($row));
			}
			if(!$review->getItem()->getMainImage()->hasSize($row['img_size']))
			{
				$review->getItem()->getMainImage()->setPath($row['img_size'], $row['img_size_path']);
			}
		}
		return $reviews;
	}

	private function mapReviewCommonInfo($row): Review
	{
		$review = new Review();
		$review->setId($row['r_id']);
		$review->setComment($row['r_comment']);
		$review->setRating($row['r_score']);
		$review->setUser($this->mapUser($row));
		$review->setDate(\DateTime::createFromFormat('Y-m-d', $row['r_date']));
		return $review;
	}

	private function mapUser($row): User
	{
		$role = new UserRole(new UserEnum($row['role_name']));
		return new User($row['u_id'], $row['u_login'], $role, $row['u_email'], $row['u_phone'], $row['u_first_name'], $row['u_second_name']);
	}

	private function mapItemCommonInfo($row): Item
	{
		$item = new Item();
		$item->setId($row['i_id']);
		$item->setTitle($row['i_title']);
		$item->setPrice($row['i_price']);
		$item->setShortDescription($row['i_short_desc']);
		$item->setSortOrder($row['i_sort_order']);
		$item->setIsActive($row['i_active']);
		return $item;
	}

	private function mapImageCommonInfo($row): ItemsImage
	{
		$image = new ItemsImage();
		$image->setId($row['img_id']);
		$image->setIsMain($row['img_is_main']);
		$image->setOriginalImagePath($row['img_orig_path']);
		return $image;
	}

	private function getSelectByItemIdStatement(int $offset, int $amount): \PDOStatement
	{
		return $this->getSelectByStatement($this->getIdsReviewByItemIdQuery($offset, $amount));
	}

	private function getSelectByUserIdStatement(int $offset, int $amount): \PDOStatement
	{
		return $this->getSelectByStatement($this->getIdsReviewByUserIdQuery($offset, $amount));
	}

	private function getSelectByStatement(string $ids): \PDOStatement
	{
		$query = "SELECT ur.ID r_id, 
                  ur.ITEM_ID i_id, 
                  ur.USER_ID u_id, 
                  ur.COMMENT r_comment, 
                  ur.SCORE r_score,
                  ur.DATE_CREATE r_date,
                  ui.ACTIVE i_active,
                  ui.SORT_ORDER i_sort_order,
                  ui.SHORT_DESC i_short_desc,
                  ui.PRICE i_price,
                  ui.TITLE i_title,
                  uoi.ID img_id,
                  uoi.IS_MAIN img_is_main,
                  uoi.PATH img_orig_path,
                  uiws.PATH img_size_path,
                  uiws.SIZE img_size,
                  uu.EMAIL u_email,
                  uu.FIRST_NAME u_first_name,
                  uu.LOGIN u_login,
                  uu.PHONE u_phone,
                  uu.SECOND_NAME u_second_name,
                  u.ID role_id,
                  u.NAME role_name
				  FROM up_review ur
				  INNER JOIN up_item ui on ur.ITEM_ID = ui.ID AND ur.ID IN ({$ids})
				  INNER JOIN up_original_image uoi on ui.ID = uoi.ITEM_ID AND uoi.IS_MAIN = 1
				  INNER JOIN up_image_with_size uiws on uoi.ID = uiws.ORIGINAL_IMAGE_ID 
				  INNER JOIN up_user uu on ur.USER_ID = uu.ID
				  INNER JOIN up_role u on uu.ROLE_ID = u.ID";
		return $this->dbConnection->prepare($query);
	}

	private function getIdsReviewByItemIdQuery(int $offset, int $amount): string
	{
		return $this->getIdsReviewBy($offset, $amount, 'ITEM_ID');
	}

	private function getIdsReviewByUserIdQuery(int $offset, int $amount): string
	{
		return $this->getIdsReviewBy($offset, $amount, 'USER_ID');
	}

	private function getIdsReviewBy(int $offset, int $amount, string $whereColumnName): string
	{
		return "SELECT * FROM(SELECT DISTINCT ID FROM up_review ur
	                                   WHERE ur.{$whereColumnName} = ?
	                                   ORDER BY ID
	                                   LIMIT {$offset}, {$amount}) ids";
	}
}