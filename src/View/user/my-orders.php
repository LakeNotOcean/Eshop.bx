<?php

/** @var array<Order> $orders */
/** @var int $amount */

/** @var $paginator */

use Up\Entity\Order\Order;
use Up\Lib\CSRF\CSRF;
use Up\Lib\FormatHelper\DateFormatterRu;
use Up\Lib\FormatHelper\WordFormatter;

?>

<link rel="stylesheet" href="/css/orders.css">

<div class="container">
	<div>Всего заказов: <?= $amount ?></div>
	<div class="order-list">
		<?= CSRF::getFormField() ?>
		<?php foreach ($orders as $order):?>
			<div class="order card">
				<div class="order-line">
					<div class="order-label">Имя покупателя:</div>
					<div class="order-value"><?= htmlspecialchars($order->getCustomerFullName()) ?></div>
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
					<div class="order-value"><?= htmlspecialchars(DateFormatterRu::format($order->getDateCreate()))?></div>
				</div>
				<div class="order-items">
					<div class="order-line">
						<div class="order-label">Товары:</div>
					</div>
					<?php
					$i = 1;
					foreach ($order->getItems() as $itemId => $purchased):
						$count = $purchased['count'];?>
						<div class="order-item">
							<div class="item-number"><?= $i ?>.</div>
							<div class="item-title"><?= htmlspecialchars($purchased['item']->getTitle()) ?></div>
							<div class="item-count"><?= $count . ' ' . WordFormatter::getPlural($count, ['штука', 'штуки', 'штук']) ?></div>
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
					<div class="order-value"><?=htmlspecialchars($order->getStatusName())?></div>
				</div>
			</div>
		<?php endforeach;?>
	</div>

	<?= $paginator?>
</div>

<script src="/js/lib/showPopup.js"></script>
<script src="/js/lib/alert-dialog.js"></script>
