<?php
/** @var $paginator */

/** @var array<\Up\Entity\Review> $reviews */

/** @var \Up\Entity\ItemDetail $item */
/** @var \Up\Entity\User\User $user */

use Up\Core\Router\URLResolver;
use Up\Lib\FormatHelper\DateFormatterRu;
use Up\Lib\FormatHelper\WordEndingResolver;

?>

<link rel="stylesheet" href="/css/more-reviews.css">
<link rel="stylesheet" href="/css/item.css">
<link rel="stylesheet" href="/css/catalog.css">


<div class="container">
	<div class="more-reviews-title">
		Отзывы на товар:
	</div>
	<div class="item card card-hover">
		<a href="<?= URLResolver::resolve('item-detail', ['id' => $item->getId()]) ?>">
			<picture>
				<source srcset="<?= '/' . $item->getMainImage()->getPath('medium', 'webp') ?>" type="image/webp">
				<img class="item-image" src="<?= '/' . $item->getMainImage()->getPath(
					'medium',
					'jpeg'
				) ?>" alt="Item Image">
			</picture>
		</a>
		<div class="item-other">
			<div class="item-other-to-top">
				<div class="item-other-header">
					<a href="<?= URLResolver::resolve('item-detail', ['id' => $item->getId()]) ?>" class="item-title">
						<?= htmlspecialchars($item->getTitle()) ?>
					</a>
					<div class="btn-add-to-favorites" title="<?= $item->getId() ?>">
						<svg class="add-to-favorites <?= $item->getIsFavorite() ? "favoriteActive" : "" ?>">
							<use xlink:href="/img/sprites.svg#heart"></use>
						</svg>
					</div>
				</div>
				<div class="item-short-description">
					<?= htmlspecialchars($item->getShortDescription()) ?>
				</div>
			</div>
			<div class="item-other-footer">
				<div class="price"><?= htmlspecialchars($item->getPrice()) ?> ₽</div>
			</div>
			<?php
			if (!$item->getIsActive()): ?>
				<div class="no-active"></div>
			<?php
			endif; ?>
		</div>
	</div>
	<div class="reviews-header">
		<svg class="star-icon">
			<use xlink:href="/img/sprites.svg#star"></use>
		</svg>
		<div class="reviews-label">
			<div class="rating"><?= ($item->getAmountReviews() > 0) ? \Up\Lib\FormatHelper\NumberFormatter::ratingFormat($item->getRating()) : '—' ?></div>
			<div class="reviews-separator">·</div>
			<div class="reviews-count"><?= ($item->getAmountReviews() > 0) ?
					"Всего отзывов: {$item->getAmountReviews()}"
					: 'нет отзывов' ?></div>
		</div>
	</div>

	<?php
	foreach ($reviews as $review): ?>
		<div class="card review-card <?= ($review->getUser()->getId() == $user->getId()) ? 'your-review-card' : '' ?>">
			<div class="item-review">
				<div class="item-review-photo">
					<img src="/img/person.jpg" alt="person">
				</div>
				<div class="item-review-data">
					<div class="item-review-name"><?= htmlspecialchars($review->getUser()->getName()) ?></div>
					<div class="item-review-date"><?= DateFormatterRu::format($review->getDate()) ?></div>
				</div>
				<div class="item-review-text">
					<?= htmlspecialchars($review->getComment()) ?>
				</div>
			</div>
			<?php if($review->getUser()->getId() == $user->getId()): ?>
				<div class="your-review-msg">Это ваш отзыв</div>
			<?php endif; ?>
			<?php if($review->getUser()->getId() == $user->getId() || $user->getRole()->getName() == \Up\Entity\User\UserEnum::Admin()): ?>
			<div class="review-remove-btn"></div>
			<input type="hidden" name="review_id" value="<?= $review->getId() ?>">
			<?php endif; ?>
		</div>
	<?php
	endforeach; ?>
	<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
	<?= $paginator ?>
</div>
<script src="/js/lib/showPopup.js"></script>
<script src="/js/review/delete-review.js"></script>
<script src="/js/add-to-favorites.js"></script>