<?php

/** @var array<\Up\Entity\Order\Order> $orders */
/** @var int $amount */

/** @var $paginator */

?>

<link rel="stylesheet" href="/css/orders.css">

<div class="container">
	<div class="order-list">
		<?= \Up\Lib\CSRF\CSRF::getFormField() ?>
		<?php foreach ($orders as $order):?>
			<div class="order card">
				<div class="order-line">
					<div class="order-label">Имя покупателя:</div>
					<div class="order-value"><?= htmlspecialchars($order->getCustomerName()) ?></div>
				</div>
				<div class="order-line">
					<div class="order-label">Телефон покупателя:</div>
					<div class="order-value"><?= htmlspecialchars($order->getPhone()) ?></div>
				</div>
				<div class="order-line">
					<div class="order-label">Почта покупателя:</div>
					<div class="order-value"><?= htmlspecialchars($order->getEmail())?></div>
				</div>
				<div class="order-line">
					<div class="order-label">Дата заказа:</div>
					<div class="order-value"><?= htmlspecialchars(\Up\Lib\FormatHelper\DateFormatterRu::format($order->getDateCreate()))?></div>
				</div>
				<div class="order-items">
					<div class="order-line">
						<div class="order-label">Товары:</div>
					</div>
					<?php
					$i = 1;
					foreach ($order->getItems() as $itemId => $purchased):?>
						<div class="order-item">
							<div class="item-number"><?= $i ?>.</div>
							<div class="item-title"><?= htmlspecialchars($purchased['item']->getTitle()) ?></div>
							<div class="item-count"><?= $purchased['count'] . ' штук' ?></div>
							<div class="item-price"><?= $purchased['item']->getPrice() ?> ₽</div>
							<div class="item-cost"><?= $purchased['count'] * $purchased['item']->getPrice() ?> ₽</div>
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
					<div class="order-label">Ваш комментарий:</div>
					<div class="order-value"><?= htmlspecialchars($order->getComment())?></div>

				</div>
				<div class="order-line">
					<div class="order-label">Статус заказа:</div>
					<div class="order-value"><?=htmlspecialchars($order->getStatus())?></div>
				</div>
			</div>
		<?php endforeach;?>
	</div>

	<?= $paginator?>
</div>

<script src="/js/lib/showPopup.js"></script>
<script src="/js/lib/alert-dialog.js"></script>
