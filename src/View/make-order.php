<?php
/** @var array<\Up\Entity\Item> $items */
?>

<link rel="stylesheet" href="/css/make-order.css">
<div class="container">
	<div class="order-items">
		<?php foreach ($items as $item):?>
			<div class="order-item">
				<img class="item-image" src="/img/2_big.webp" alt="item-main-image">
				<div class="item-info">
					<div class="item-title"><?= $item->getTitle()?></div>
					<div class="item-price"><?= $item->getPrice()?> ₽</div>
				</div>
			</div>
		<?php endforeach;?>
	</div>
	<form action="/finishOrder" method="post" enctype="multipart/form-data" class="user-data">
		<?php foreach ($items as $item):?>
			<input type="hidden" name="itemIds[]" value="<?= $item->getId()?>" />
		<?php endforeach;?>
		<div class="user-data-title">Данные покупателя</div>
		<div class="user-name">
			<label for="name">
				<input type="text" id="name" name="name" placeholder="Имя">
			</label>
			<label for="second-name">
				<input type="text" id="second-name" name="second-name" placeholder="Фамилия">
			</label>
		</div>
		<div class="user-contact">
			<label for="phone">
				<input type="tel" id="phone" name="phone" placeholder="Телефон">
			</label>
			<label for="email">
				<input type="email" id="email" name="email" placeholder="E-mail">
			</label>
		</div>
		<label for="comment">
			<textarea name="comment" id="comment" rows="10" class="order-comment" placeholder="Комментарий к заказу"></textarea>
		</label>
		<input type="submit" value="Подтвердить" class="btn-confirm">
	</form>
</div>
