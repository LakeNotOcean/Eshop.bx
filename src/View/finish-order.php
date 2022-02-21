<?php
/** @var array<Item> $items */

use Up\Entity\Item;

?>

<link rel="stylesheet" href="/css/finish-order.css">
<div class="container">
	<div class="access-message">Ваш заказ успешно оформлен</div>
	<div class="wait-message">В ближайшее время с Вами свяжется наш менеджер для подтверждения заказа</div>
	<div class="order-title">Ваш заказ:</div>
	<div class="order-items">
		<?php
		$i = 1;
		foreach ($items as $item):?>
			<div class="order-item">
				<div class="item-number"><?= $i ?>.</div>
				<div class="item-title"><?= htmlspecialchars($item->getTitle()) ?></div>
				<div class="item-price"><?= $item->getPrice() ?> ₽</div>
			</div>
			<?php
			$i++;
		endforeach; ?>
	</div>

	<a href="/" class="btn btn-normal">Вернуться на главную</a>

</div>
