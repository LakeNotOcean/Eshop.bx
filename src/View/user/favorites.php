<?php

/** @var array<UserItem> $favoriteItems */
/** @var $paginator */

use Up\Core\Router\URLResolver;
use Up\Entity\UserItem;
use Up\Lib\CSRF\CSRF;
use Up\Lib\FormatHelper\WordFormatter;

?>

<script src="/js/user/user-menu.js"></script>

<link rel="stylesheet" href="/css/catalog.css">
<link rel="stylesheet" href="/css/favorites.css">

<div class="container">

	<div class="main-title">Список избранных товаров</div>

	<?php if (count($favoriteItems) === 0): ?>
		<div class="message-no-results">
			Вам ничего не нравится. Нажмите на сердечко, чтобы добавить товар в избранное.
		</div>
	<?php endif; ?>

	<div class="item-list">
		<?php foreach ($favoriteItems as $item):?>
		<div class="item card card-hover">
			<a href="<?= URLResolver::resolve('item-detail', ['id' => $item->getId()]) ?>">
				<picture>
					<source srcset="<?='/' . $item->getMainImage()->getPath('medium', 'webp') ?>" type="image/webp">
					<img class="item-image" src="<?= '/' . $item->getMainImage()->getPath('medium', 'jpeg') ?>" alt="Item Image">
				</picture>
			</a>
			<div class="item-other">
				<div class="item-other-to-top">
					<div class="item-other-header">
						<a href="<?= URLResolver::resolve('item-detail', ['id' => $item->getId()]) ?>" class="item-title">
							<?= htmlspecialchars($item->getTitle()) ?>
						</a>
						<?= CSRF::getFormField() ?>
						<div class="btn-add-to-favorites" title="<?= $item->getId()?>">
							<svg class="add-to-favorites <?= $item->getIsFavorite() ? "favoriteActive" : ""?>">
								<use xlink:href="/img/sprites.svg#heart"></use>
							</svg>
						</div>
					</div>
					<div class="item-short-description">
						<?=htmlspecialchars($item->getShortDescription())?>
					</div>
				</div>
				<div class="item-other-footer">
					<div class="rating">
						<svg class="star-icon">
							<use xlink:href="./img/sprites.svg#star"></use>
						</svg>
						<div class="rating-value">
							<?= ($item->getAmountReviews() > 0) ? \Up\Lib\FormatHelper\NumberFormatter::ratingFormat($item->getRating()) : '—' ?>
						</div>
						<div class="review-count">
							<?= ($item->getAmountReviews() > 0) ?
								"({$item->getAmountReviews()} "
								. WordFormatter::getPlural($item->getAmountReviews(), array('отзыв', 'отзыва', 'отзывов'))
								. ')'
								: 'Отзывов пока нет.' ?>
						</div>
					</div>
					<div class="price"><?= htmlspecialchars($item->getPrice()) ?> ₽</div>
				</div>
			</div>
		</div>
		<?php endforeach;?>

		<?= $paginator?>
	</div>
</div>

<script src="/js/lib/showPopup.js"></script>
<script src="/js/add-to-favorites.js"></script>
