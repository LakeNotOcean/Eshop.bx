<?php

/** @var array<\Up\Entity\Order> $orders */

?>

<link rel="stylesheet" href="/css/orders.css">

<div class="container">

	<div class="order-list">
		<?php foreach ($orders as $order):?>
			<div class="order card">
				<div class="order-line">
					<div class="order-label">Имя покупателя:</div>
					<div class="order-value"><?= $order->getCustomerName()?></div>
				</div>
				<div class="order-line">
					<div class="order-label">Телефон покупателя:</div>
					<div class="order-value"><?= $order->getPhone()?></div>
				</div>
				<div class="order-line">
					<div class="order-label">Почта покупателя:</div>
					<div class="order-value"><?= $order->getEmail()?></div>
				</div>
				<div class="order-items">
					<div class="order-line">
						<div class="order-label">Товары:</div>
					</div>
					<?php
					$i = 1;
					foreach ($order->getItems() as $item):?>
						<div class="order-item">
							<div class="item-number"><?= $i ?>.</div>
							<div class="item-title"><?= $item->getTitle() ?></div>
							<div class="item-price"><?= $item->getPrice() ?> ₽</div>
						</div>
						<?php
						$i++;
					endforeach; ?>
					<div class="order-line">
						<div class="order-label">Итого:</div>
						<div class="order-total"><?= $order->getTotalCost()?> ₽</div>
					</div>
				</div>
				<div class="order-line">
					<div class="order-label">Комментарий покупателя:</div>
					<div class="order-value"><?= $order->getComment()?></div>
				</div>
			</div>
		<?php endforeach;?>
	</div>
</div>
