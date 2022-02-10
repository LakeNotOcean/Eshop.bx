<?php
/** @var \Up\Entity\Item $item */

$itemSpecs = [1 => new \Up\Entity\SpecificationCategory(
	1,'Заводские данные',0, [
		 new \Up\Entity\Specification(1, 'Гарантия', 0, '24 месяца'),
		 new \Up\Entity\Specification(2, 'Страна-производитель', 1, 'Китай'),
	 ]
), new \Up\Entity\SpecificationCategory(
	  2,'Внешний вид',1, [
		   new \Up\Entity\Specification(3, 'Основной цвет', 0, 'черный'),
		   new \Up\Entity\Specification(4, 'Дополнительный цвет', 1, 'серый'),
		   new \Up\Entity\Specification(5, 'Подсветка', 2, 'нет'),
	   ]
)];

?>

<link rel="stylesheet" href="/css/item.css">
<link rel="stylesheet" href="/lib/lightbox/css/lightbox.css">
<div class="container">
	<a class="anchor" id="main"></a>
	<div class="item-header">
		<div class="item-title"><?= $item->getTitle()?></div>
		<div class="add-to-favorites">
			<svg class="add-to-favorites-icon">
				<use xlink:href="/img/sprites.svg#heart"></use>
			</svg>
			<div class="add-to-favorites-label">В избранное</div>
		</div>
	</div>
	<div class="item-main">

		<div class="item-main-sidebar">
			<div class="item-main-images card-outline">
				<div class="item-main-images-main">
					<a href="/img/2_big.webp" data-lightbox='item-pic'>
						<img src="/img/2_big.webp" alt="item-main-image">
					</a>
				</div>

				<div class="item-main-images-all">
<!--						<a href=bigImage> <img src=smallImage> </a>-->
					<a href="/img/2-1_big.webp" data-lightbox='item-pic'>
						<img src="/img/2-1_small.webp" alt="videocard">
					</a>
					<a href="/img/2-2_big.webp" data-lightbox='item-pic'>
						<img src="/img/2-2_small.webp" alt="videocard">
					</a>
				</div>

			</div>
			<div class="scroll-menu card-outline">
				<a class="scroll-menu-item" href="#main">Купить</a>
				<a class="scroll-menu-item" href="#specs">Характеристики</a>
				<a class="scroll-menu-item" href="#description">Описание</a>
				<a class="scroll-menu-item" href="#reviews">Отзывы</a>
			</div>
		</div>

		<div class="item-main-container">

			<div class="item-main-header">
				<div class="item-main-short-desc"><?= $item->getShortDescription()?></div>
				<div class="item-buy card-outline">
					<div class="item-buy-header">
						<div class="buy-price">
							<div class="buy-price-value"><?= $item->getPrice() ?> ₽</div>
							<div class="buy-price-measure">/ штуку</div>
						</div>
						<a href="#reviews" class="buy-reviews">
							<svg class="star-icon">
								<use xlink:href="/img/sprites.svg#star"></use>
							</svg>
							<div class="buy-reviews-label">
								<div class="buy-rating">4.8</div>
								<div class="buy-reviews-separator">·</div>
								<div class="buy-reviews-count">6 отзывов</div>
							</div>
						</a>
					</div>
					<a class="btn-buy" href="/makeOrder/<?= $item->getId()?>">Купить</a>
				</div>
			</div>

			<div class="item-main-specs">
				<a class="anchor" id="specs"></a>
				<div class="item-section-title">Характеристики</div>
				<?php foreach ($itemSpecs as $category):?>
					<div class="spec-category"><?= $category->getName()?></div>
					<?php foreach ($category->getSpecificationList() as $spec):?>
						<div class="item-spec">
							<div class="item-spec-name"><?= $spec->getName()?></div>
							<div class="item-spec-value"><?= $spec->getValue()?></div>
						</div>
					<?php endforeach;?>
				<?php endforeach;?>
			</div>

			<div class="item-description">
				<a class="anchor" id="description"></a>
				<div class="item-section-title">Описание</div>
				<div class="description-text"><?= $item->getShortDescription() ?> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid architecto aspernatur atque aut culpa dolor est fugit, hic illo impedit ipsa ipsam ipsum iure iusto laborum laudantium modi natus nihil nostrum officia optio pariatur praesentium quae quas qui quo recusandae sed tempore ut veritatis. Ab accusamus ad at commodi consectetur debitis deleniti ducimus eligendi eveniet fugiat incidunt, ipsam labore laborum, laudantium, libero nemo nostrum quo repellat sapiente sequi sint tempora unde vitae! Ab adipisci, aliquam asperiores assumenda consequuntur culpa debitis delectus deleniti doloremque inventore ipsa iusto laborum molestiae nulla, officia officiis optio quam quisquam quos sit suscipit tempora totam ut!</div>
			</div>

			<div class="item-reviews">
				<a class="anchor" id="reviews"></a>
				<div class="reviews-header">
					<svg class="star-icon">
						<use xlink:href="/img/sprites.svg#star"></use>
					</svg>
					<div class="reviews-label">
						<div class="rating">4.8</div>
						<div class="reviews-separator">·</div>
						<div class="reviews-count">6 отзывов</div>
					</div>
				</div>
				<div class="item-review">
					<div class="item-review-photo">
						<img src="/img/<?= $item->getId() ?>.png" alt="person">
					</div>
					<div class="item-review-data">
						<div class="item-review-name">Юлия</div>
						<div class="item-review-date">11 января 2022 г.</div>
					</div>
					<div class="item-review-text">
						Супер качество сборки и легкость смены батареи!работает даже на пузе или волосатой моей ноге!очень скользкие пластинки на подошве-не надо звать трактор!оптика не светит!!!-так что не смотрите на линзу!!!-скорей всего батарейки хватит года на два по этой причине.
					</div>
				</div>

				<div class="item-review">
					<div class="item-review-photo">
						<img src="/img/<?= $item->getId() ?>.png" alt="person">
					</div>
					<div class="item-review-data">
						<div class="item-review-name">Юлия</div>
						<div class="item-review-date">11 января 2022 г.</div>
					</div>
					<div class="item-review-text">
						Супер качество сборки и легкость смены батареи!работает даже на пузе или волосатой моей ноге!очень скользкие пластинки на подошве-не надо звать трактор!оптика не светит!!!-так что не смотрите на линзу!!!-скорей всего батарейки хватит года на два по этой причине.
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

<script src="/lib/lightbox/js/lightbox-plus-jquery.js"></script>
<script src="/js/scroll.js"></script>
