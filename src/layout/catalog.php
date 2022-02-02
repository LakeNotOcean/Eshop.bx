<?php
/** @var array<Model\Item> $items */
/** @var int $result_count */
?>

<link rel="stylesheet" href="./css/catalog.css">
<div class="container">
	<div class="search_result_count">Компьютерные мыши: найдено <?= $result_count?> штук</div>
	<div class="filters-item-list-row">
		<div class="filters-column">
			<div class="filters">
				<div class="filter-category">
					<div class="price-category"></div>
				</div>
			</div>
		</div>
		<div class="item-list">

			<?php foreach($items as $item) :?>

			<div class="item">
				<img src="../img/<?= $item->getId()?>.png" alt="item-image" class="item-image">
				<div class="item-other">
					<div class="item-other-to-top">
						<div class="item-other-header">
							<div class="item-title"><?= $item->getTitle()?></div>
							<svg class="add-to-favorites">
								<use xlink:href="./img/sprites.svg#heart"></use>
							</svg>
						</div>
						<div class="item-short-description">
							<?= $item->getShortDesc()?>
						</div>
					</div>
					<div class="item-other-footer">
						<div class="rating">
							<svg class="star-icon">
								<use xlink:href="./img/sprites.svg#star"></use>
							</svg>
							<div class="rating-value"><?= (float)random_int(40,50)/10?></div>
							<div class="review-count">(<?= random_int(5,50)?> отзывов)</div>
						</div>
						<div class="price"><?= $item->getFormattedPrice()?> ₽</div>
					</div>
				</div>
			</div>

			<?php endforeach;?>

		</div>
	</div>
</div>
<script src="./js/fixed-filters.js"></script>
