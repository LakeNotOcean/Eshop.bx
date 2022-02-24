<?php
/** @var \Up\Entity\UserItem $item */
/** @var array<UP\Entity\Item> $similarItems */
/** @var array<\Up\Entity\Review> $reviews */
/** @var bool $itemIsPurchased */
/** @var bool $reviewIsWritten */
/** @var bool $isAuthenticated */

use Up\Lib\FormatHelper\DateFormatterRu;
use Up\Lib\FormatHelper\NumberFormatter;
use Up\Lib\FormatHelper\WordEndingResolver;
?>

<link rel="stylesheet" href="/css/item.css">
<link rel="stylesheet" href="/css/lib/fontawesome-all.css">
<link rel="stylesheet" href="/css/rating.css">

<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
<div class="opened-images" style="display: none;">
	<div class="open-images-container">
		<div class="open-images-header">
			<div class="btn-back"></div>
			<div class="btn-add-to-favorites" title="<?= $item->getId()?>">
				<svg class="add-to-favorites <?= $item->getIsFavorite() ? "favoriteActive" : ""?>">
					<use xlink:href="/img/sprites.svg#heart"></use>
				</svg>
				<div class="add-to-favorites-label">В избранное</div>
			</div>
		</div>
		<div class="open-images-main">
			<div class="image-list">
				<?php foreach ($item->getImages() as $itemImage):?>
				<picture>
					<source srcset="<?='/' . $itemImage->getPath('big', 'webp') ?>" type="image/webp">
					<img src="<?='/' . $itemImage->getPath('big', 'jpeg')?>" alt="item-main-image" class="item-image">
				</picture>
				<?php endforeach;?>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<a class="anchor" id="main"></a>
	<div class="item-header">
		<div class="item-title"><?= htmlspecialchars($item->getTitle()) ?></div>
		<div class="btn-add-to-favorites" title="<?= $item->getId()?>">
			<svg class="add-to-favorites <?= $item->getIsFavorite() ? "favoriteActive" : ""?>">
				<use xlink:href="/img/sprites.svg#heart"></use>
			</svg>
			<div class="add-to-favorites-label">В избранное</div>
		</div>
	</div>
	<div class="item-main">

		<div class="item-main-sidebar">
			<div class="item-main-images card-outline">
				<div>
					<picture>
						<source srcset="<?='/' . $item->getMainImage()->getPath('medium', 'webp') ?>" type="image/webp">
						<img src=" <?='/'.$item->getMainImage()->getPath('medium', 'jpeg')?>" alt="item-main-image" class="main-image">
					</picture>
				</div>
				<div class="images-other">
					<?php foreach (array_slice($item->getImages(), 0, 4) as $image): ?>
						<?php if (!$image->isMain()): ?>
						<picture>
							<source srcset="<?='/' . $image->getPath('small', 'webp') ?>" type="image/webp">
							<img src="<?='/' . $image->getPath('small', 'jpeg') ?>" alt="" class="other-image">
						</picture>

						<?php endif; ?>
					<?php endforeach; ?>
				</div>

			</div>
			<div class="scroll-menu card-outline">
				<a class="scroll-menu-item" title="main">Купить</a>
				<a class="scroll-menu-item" title="specs">Характеристики</a>
				<a class="scroll-menu-item" title="description">Описание</a>
				<a class="scroll-menu-item" title="reviews">Отзывы</a>
				<?php
				if (!empty($similarItems)){?>
				<a class="scroll-menu-item" title="similar">Похожие товары</a>
				<?}?>
			</div>
		</div>

		<div class="item-main-container">

			<div class="item-main-header">
				<div class="item-main-short-desc">
					<div class="item-tags">
						<?php foreach ($item->getTags() as $tag): ?>
							<div class="item-tag"><?= $tag->getName() ?></div>
						<?php endforeach; ?>
					</div>
					<div class="item-main-short-desc-text">
						<?= htmlspecialchars($item->getShortDescription()) ?>
					</div>
				</div>
				<div class="item-buy card-outline">
					<div class="item-buy-header">
						<div class="buy-price">
							<div class="buy-price-value"><?= htmlspecialchars($item->getPrice()) ?> ₽</div>
							<div class="buy-price-measure">/ штуку</div>
						</div>
						<a class="buy-reviews" title="reviews">
							<svg class="star-icon">
								<use xlink:href="/img/sprites.svg#star"></use>
							</svg>
							<div class="buy-reviews-label">
								<div class="buy-rating"><?= ($item->getAmountReviews() > 0) ? NumberFormatter::ratingFormat($item->getRating()) : '—' ?></div>
								<div class="buy-reviews-separator">·</div>
								<div class="buy-reviews-count"><?= ($item->getAmountReviews() > 0) ?
										"({$item->getAmountReviews()} "
										. WordEndingResolver::resolve($item->getAmountReviews(), array('отзыв','отзыва','отзывов'))
										. ')'
										: 'нет отзывов' ?></div>
							</div>
						</a>
					</div>
					<div id="item-cart-container">
						<?php if (!isset($isItemAdded) || !$isItemAdded): ?>
							<div id="cart-add-item">
								<input class="item-id" type="hidden" name="item-id" value="<?= $item->getId() ?>">
								<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
								<div id="send-item-id" class="btn-buy">Добавить товар в корзину</div>
							</div>
						<?php else: ?>
							<div id="cart-item-added">
								<input class="item-id" type="hidden" name="item-id" value="<?= $item->getId() ?>">
								<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
								<div id="send-item-id" class="btn-buy btn-item-added">Удалить товар из корзины</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="item-main-specs">
				<a class="anchor" id="specs"></a>
				<div class="item-section-title">Характеристики</div>
				<?php
				foreach ($item->getSpecificationCategoriesList() as $category): ?>
					<div class="spec-category"><?= htmlspecialchars($category->getName()) ?></div>
					<?php
					foreach ($category->getSpecifications() as $spec):
						if ($spec->getValue()):
						?>
						<div class="item-spec">
							<div class="item-spec-name"><?= htmlspecialchars($spec->getName()) ?></div>
							<div class="item-spec-value"><?= htmlspecialchars($spec->getValue()) ?></div>
						</div>
					<?php endif;
					endforeach; ?>
				<?php
				endforeach; ?>
			</div>

			<div class="item-description">
				<a class="anchor" id="description"></a>
				<div class="item-section-title">Описание</div>
				<div class="description-text"><?= htmlspecialchars($item->getFullDescription()) ?> </div>

				<div class="item-reviews">
					<a class="anchor" id="reviews"></a>
					<div class="reviews-header">
						<svg class="star-icon">
							<use xlink:href="/img/sprites.svg#star"></use>
						</svg>
						<div class="reviews-label">
							<div class="rating"><?= ($item->getAmountReviews() > 0) ? NumberFormatter::ratingFormat($item->getRating()) : '—' ?></div>
							<div class="reviews-separator">·</div>
							<div class="reviews-count"><?= ($item->getAmountReviews() > 0) ?
									"({$item->getAmountReviews()} "
									. WordEndingResolver::resolve($item->getAmountReviews(), array('отзыв','отзыва','отзывов'))
									. ')'
									: 'нет отзывов' ?></div>
						</div>
					</div>
					<?php foreach ($reviews as $review): ?>
					<div class="item-review">
						<div class="item-review-photo">
							<img src="/img/person.jpg" alt="person">
						</div>
						<div class="item-review-data">
							<div class="item-review-name"><?= htmlspecialchars($review->getUser()->getName()) ?></div>
							<div class="item-review-date"><?= DateFormatterRu::format($review->getDate())  ?></div>
						</div>
						<div class="item-review-text">
							<?= htmlspecialchars($review->getComment()) ?>
						</div>
					</div>
					<?php endforeach; ?>
					<?php if($item->getAmountReviews() > 3): ?>
					<a href="">Посмотреть больше отзывов</a>
					<?php endif; ?>
				</div>
			</div>
			<div class="review-send-section">
				<?php if(!$isAuthenticated): ?>
				<div>Оставлять отзывы могут только авторизированные пользователи</div>
				<?php elseif (!$itemIsPurchased): ?>
				<div>Вам сначала нужно купить этот товар, прежде чем оставить отзыв</div>
				<?php elseif ($reviewIsWritten): ?>
				<div>Вы уже оставляли отзыв к этому товару</div>
				<?php else: ?>
				<form class="review-send" action="<?= \Up\Core\Router\URLResolver::resolve('add-review') ?>" method="post">
					<div class="rating-title">Оставьте отзыв о товаре:</div>
					<div class="rating-container">
						<div class="review_stars_wrap">
							<div id="review_stars">
								<input id="star-4" type="radio" name="rating" value="5"/>
								<label title="Отлично" for="star-4">
									<i class="fas fa-star"></i>
								</label>
								<input id="star-3" type="radio" name="rating" value="4"/>
								<label title="Хорошо" for="star-3">
									<i class="fas fa-star"></i>
								</label>
								<input id="star-2" type="radio" name="rating" checked="checked" value="3"/>
								<label title="Нормально" for="star-2">
									<i class="fas fa-star"></i>
								</label>
								<input id="star-1" type="radio" name="rating" value="2"/>
								<label title="Плохо" for="star-1">
									<i class="fas fa-star"></i>
								</label>
								<input id="star-0" type="radio" name="rating" value="1"/>
								<label title="Ужасно" for="star-0">
									<i class="fas fa-star"></i>
								</label>
							</div>
						</div>
					</div>
					<textarea class="review-send-text" name="text_review" placeholder="Введите свой отзыв..."></textarea>
					<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
					<input name="item_id" type="hidden" value="<?= $item->getId() ?>">
					<div class="btn btn-add">Отправить отзыв</div>
				</form>
				<?php endif; ?>
			</div>
			<?php if (!empty($similarItems)):?>
			<div class="similar-item-section">
				<a class="anchor" id="similar"></a>
				<div class="item-section-title">Похожие товары</div>
				<div class="similar-item-main-section">
					<div class="btn-back similar-item-cards-left-arrow"></div>
					<div class="slider-wrapper">
					<div class="similar-item-cards-section">
					<?php
					foreach (array_values($similarItems) as $index => $similarItem): ?>
						<a href="/item/<?=$similarItem->getId()?>" class="similar-item-card card-outline">
							<div class="similar-item-image-section">
								<picture>
									<source srcset="<?='/' . $similarItem->getMainImage()->getPath('small', 'webp') ?>" type="image/webp">
									<img class="similar-item-image" src="<?= '/' . $similarItem->getMainImage()->getPath('small', 'jpeg') ?>" alt="Item Image">
								</picture>
							</div>
							<div class="similar-item-body-section">
								<div class="similar-item-body-title"><?= htmlspecialchars($similarItem->getTitle()) ?></div>
								<div class="similar-item-body-price"><?= htmlspecialchars($similarItem->getPrice()) ?> ₽</div>
							</div>
						</a>
					<?php endforeach; ?>
					</div>
					</div>
					<div class="btn-back similar-item-cards-right-arrow"></div>
				</div>
			</div>
			<?php endif;?>
		</div>
	</div>
</div>

<script src="/js/lib/scroll.js"></script>
<script src="/js/lib/fix-node.js"></script>
<script src="/js/item/fixed-scroll-menu.js"></script>

<script src="/js/item/open-images.js"></script>
<script src="/js/item/scroll-similar-items.js"></script>

<script src="/js/lib/showPopup.js"></script>
<script src="/js/add-to-favorites.js"></script>

<script src="/js/review/send-review.js"></script>

<script src="/js/csrf.js" type="module"></script>
<script src="/js/cart/add-item.js" type="module"></script>