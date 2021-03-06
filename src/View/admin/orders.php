<?php

/** @var array<Order> $orders */
/** @var string $query */

/** @var $paginator */

use Up\Core\Router\URLResolver;
use Up\Entity\Order\Order;
use Up\Lib\CSRF\CSRF;
use Up\Lib\FormatHelper\DateFormatterRu;
use Up\Lib\FormatHelper\WordFormatter;

?>

<link rel="stylesheet" href="/css/orders.css">

<div class="container">
	<div class="order-line">
		<div class="order-label">Статус заказа:</div>
		<div class="order-status-filter">
			<select id="statusSelect" name="item-type">
				<option value="IN_PROCESSING">В обработке</option>
				<option value="DELIVERY">Ожидает доставки</option>
				<option value="DONE">Завершён</option>
				<option value="CANCELLED">Отменён</option>
			</select>
		</div>
		<form action="<?= URLResolver::resolve('admin:orders') ?>" method="get" enctype="multipart/form-data" class="local-search">
			<input type="text" id="query" name="query" class="search-field" placeholder="Поиск заказов"
				   value="<?= htmlspecialchars($query)?>">
			<div class="search-icon">
				<div></div>
			</div>
		</form>
	</div>
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
					<div class="order-label">Комментарий покупателя:</div>
					<div class="order-value"><?= htmlspecialchars($order->getComment())?></div>
				</div>
				<div class="order-line">
					<div class="order-label">Статус заказа:</div>
					<div class="order-status-filter">
						<select id="statusSelect<?= $order->getId()?>" class="order-status">
							<option value="IN_PROCESSING" <?= $order->getStatus()->getValue() === 'IN_PROCESSING' ? 'selected' : ''?>>В обработке</option>
							<option value="DELIVERY" <?= $order->getStatus()->getValue() === 'DELIVERY' ? 'selected' : ''?>>Ожидает доставки</option>
							<option value="DONE" <?= $order->getStatus()->getValue() === 'DONE' ? 'selected' : ''?>>Завершён</option>
							<option value="CANCELLED" <?= $order->getStatus()->getValue() === 'CANCELLED' ? 'selected' : ''?>>Отменён</option>
						</select>
					</div>
					<div id="btnDelete<?= $order->getId()?>" class="btn btn-delete">Удалить</div>
				</div>
			</div>
		<?php endforeach;?>
	</div>

	<?= $paginator?>
</div>

<script src="/js/lib/showPopup.js"></script>
<script src="/js/lib/alert-dialog.js"></script>
<script src="/js/admin-orders/change-status.js"></script>
<script src="/js/admin-orders/manage-orders.js"></script>
