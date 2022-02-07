<?php
$item = $items[0];
?>
<link rel="stylesheet" href="./css/catalog.css">
<link rel="stylesheet" href="./css/item.css">
<div class="container">
	<div class="item-header">
		<p class="item-title"><?= $item->getTitle() ?></p>
		<div class="favourites">
			<svg class="add-to-favorites">
				<use xlink:href="./img/sprites.svg#heart"></use>
			</svg>
			В избранное
		</div>
	</div>
	<div class="item-main">
		<div class="item-main-sidebar">
			<div class="item-main-images">
				<img src="../img/<?= $item->getId() ?>.png" alt="videocard">
			</div>
			<div class="item-main-options">
				<p>Купить</p><br>
				<p>Характеристики</p><br>
				<p>Описание</p><br>
				<p>Отзывы</p>
			</div>
		</div>
		<div class="item-main-container">
			<div class="item-main-header">
				<div class="item-main-short-desc">
					<?= $item->getShortDescription() ?>
				</div>
				<div class="item-main-buy">
					<div class="item-main-buy-price">
						<?= $item->getPrice() ?> ₽ / штука
					</div>
					<div class="item-main-buy-reviews">
						<svg class="star-icon">
							<use xlink:href="./img/sprites.svg#star"></use>
						</svg>
						4.8 (6 отзывов)
					</div>
					<a class="item-main-buy-button" href="#">Купить</a>
				</div>
			</div>
			<div class="item-main-specs">
				<div class="item-title">
					Характеристики
				</div>
				<div class="item-main-specs-subtitle">Заводские данные</div>
				<div class="item-main-specs-name">Гарантия</div>
				<div class="item-main-specs-value">24 months</div>
			</div>
			<div class="item-main-description">
				<div class="item-title">Описание</div>
				<?= $item->getShortDescription() ?>
			</div>
			<div class="item-main-reviews">
				<div class="item-title">
					<svg class="star-icon">
						<use xlink:href="./img/sprites.svg#star"></use>
					</svg>
					4.8 (6 отзывов)
				</div>
				<div class="item-review">
					<div class="item-review-photo">
						<img src="../img/<?= $item->getId() ?>.png" alt="person">
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
