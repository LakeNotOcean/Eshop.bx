<?php
$item = $items[0];
?>
<link rel="stylesheet" href="./css/catalog.css">
<link rel="stylesheet" href="./css/item.css">
<link rel="stylesheet" href="./lib/lightbox/css/lightbox.css">
<div class="container">
	<a class="anchor" id="main"></a>
	<div class="item-wrapper">
		<div class="item-header">
			<p class="item-title"><?= $item->getTitle() ?></p>
			<div class="favourites">
				<svg class="add-to-favorites">
					<use xlink:href="./img/sprites.svg#heart"></use>
				</svg>
				<p>В избранное</p>
			</div>
		</div>
		<div class="item-main">
			<div class="item-main-sidebar">
				<div class="item-main-images">
					<div class="item-main-images-main">
						<a href="../img/2_big.webp" data-lightbox='item-pic'>
							<img src="../img/2_big.webp" alt="videocard">
						</a>
					</div>

					<div class="item-main-images-all">
<!--						<a href=bigImage> <img src=smallImage> </a>-->
						<a href="../img/2-1_big.webp" data-lightbox='item-pic'>
							<img src="../img/2-1_small.webp" alt="videocard">
						</a>
						<a href="../img/2-2_big.webp" data-lightbox='item-pic'>
							<img src="../img/2-2_small.webp" alt="videocard">
						</a>
					</div>


				</div>
				<div class="item-main-options">
					<a href="#main">Купить</a>
					<a href="#specs">Характеристики</a>
					<a href="#description">Описание</a>
					<a href="#reviews">Отзывы</a>
				</div>
			</div>
			<div class="item-main-container">
				<div class="item-main-header">
					<div class="item-main-short-desc">
						<?= $item->getShortDescription() ?>
					</div>
					<div class="item-main-buy">
						<div class="item-main-buy-price">
							<?= $item->getPrice() ?> ₽
						</div>
						<div class="item-main-buy-reviews">
							<svg class="star-icon">
								<use xlink:href="./img/sprites.svg#star"></use>
							</svg>
							<p>4.8 · 6 отзывов</p>
						</div>
						<a class="item-main-buy-button" href="#">Купить</a>
					</div>
				</div>
				<div class="item-main-specs">
					<a class="anchor" id="specs"></a>
					<div class="item-title">
						Характеристики
					</div>

					<div class="item-main-specs-subtitle">Заводские данные</div>
					<div class="item-main-specs-element">
						<div class="item-main-specs-name">Гарантия</div>
						<div class="item-main-specs-value">24 months</div>
					</div>

					<div class="item-main-specs-element">
						<div class="item-main-specs-name">Цвет</div>
						<div class="item-main-specs-value">черный</div>
					</div>

					<div class="item-main-specs-subtitle">Подключение</div>
					<div class="item-main-specs-element">
						<div class="item-main-specs-name">Интерфейс подключения</div>
						<div class="item-main-specs-value">USB-A</div>
					</div>

				</div>
				<div class="item-main-description">
					<a class="anchor" id="description"></a>
					<div class="item-title">Описание</div>
					<p><?= $item->getShortDescription() ?> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquid architecto aspernatur atque aut culpa dolor est fugit, hic illo impedit ipsa ipsam ipsum iure iusto laborum laudantium modi natus nihil nostrum officia optio pariatur praesentium quae quas qui quo recusandae sed tempore ut veritatis. Ab accusamus ad at commodi consectetur debitis deleniti ducimus eligendi eveniet fugiat incidunt, ipsam labore laborum, laudantium, libero nemo nostrum quo repellat sapiente sequi sint tempora unde vitae! Ab adipisci, aliquam asperiores assumenda consequuntur culpa debitis delectus deleniti doloremque inventore ipsa iusto laborum molestiae nulla, officia officiis optio quam quisquam quos sit suscipit tempora totam ut!</p>
				</div>
				<div class="item-main-reviews">
					<a class="anchor" id="reviews"></a>
					<div class="item-main-reviews-header">
						<svg class="star-icon">
							<use xlink:href="./img/sprites.svg#star"></use>
						</svg>
						<div class="item-title">4.8 · 6 отзывов</div>
					</div>
					<div class="item-review">
						<div class="item-review-photo">
							<img src="../img/<?= $item->getId() ?>.jpg" alt="person">
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
							<img src="../img/<?= $item->getId() ?>.jpg" alt="person">
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

</div>

<script src="./lib/lightbox/js/lightbox-plus-jquery.js"></script>
<script src="./js/main.js"></script>
