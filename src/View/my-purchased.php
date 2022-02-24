<?php
/** @var $paginator */

/** @var array<\Up\Entity\Review> $reviews */
/** @var array<\Up\Entity\UserItem> $items */

use Up\Core\Router\URLResolver;
use Up\Lib\FormatHelper\DateFormatterRu;
use Up\Lib\FormatHelper\WordEndingResolver;

?>

<link rel="stylesheet" href="/css/more-reviews.css">
<link rel="stylesheet" href="/css/item.css">
<link rel="stylesheet" href="/css/catalog.css">
<link rel="stylesheet" href="/css/my-reviews.css">


<div class="container">
	<div class="title-my-reviews">Купленные вами товары:</div>
	<?php foreach ($items as $item): ?>
		<div class="card item-card-my-reviews">
			<div class="item item-my-reviews">
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
						<div class="item-other-footer">
							<div class="price"><?= htmlspecialchars($item->getPrice()) ?> ₽</div>
						</div>
					</div>
					<?php
					if (!$item->getIsActive()): ?>
						<div class="no-active"></div>
					<?php
					endif; ?>
				</div>
			</div>
			<div class="delimiter"></div>
			<?php if (array_key_exists($item->getId(), $reviews)): ?>
			<div class="review review-my-reviews">
				<div class="item-review">
					<div class="item-review-photo">
						<img src="/img/person.jpg" alt="person">
					</div>
					<div class="item-review-data">
						<div class="item-review-name"><?= htmlspecialchars($reviews[$item->getId()]->getUser()->getName()) ?></div>
						<div class="item-review-date"><?= DateFormatterRu::format($reviews[$item->getId()]->getDate()) ?></div>
					</div>
					<div class="item-review-text">
						<?= htmlspecialchars($reviews[$item->getId()]->getComment()) ?>
					</div>
				</div>
				<div class="review-remove-btn"></div>
				<input type="hidden" name="review_id" value="<?= $reviews[$item->getId()]->getId() ?>">
			</div>
			<?php else: ?>
			<div class="title-my-reviews">
				Вы еще не оставили отзыв на этот товар! Перейдите на страницу товара, чтобы сделать это.
			</div>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
	<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
	<?= $paginator ?>
</div>
<script src="/js/lib/showPopup.js"></script>
<script src="/js/review/delete-review.js"></script>
<script src="/js/add-to-favorites.js"></script>