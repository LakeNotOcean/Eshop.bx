<?php

/** @var array<\Up\Entity\Order\Order> $orders */
/** @var int $currentPage */
/** @var int $pagesAmount */
/** @var string $query */

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
		<form action="/admin/getOrders" method="get" enctype="multipart/form-data" class="search">
			<input type="text" id="query" name="query" class="search-field" placeholder="Поиск по сайту"
				   value="<?= htmlspecialchars($query)?>">
			<div class="search-icon">
				<div></div>
			</div>
		</form>
	</div>
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
				<div class="order-items">
					<div class="order-line">
						<div class="order-label">Товары:</div>
					</div>
					<?php
					$i = 1;
					foreach ($order->getItems() as $item):?>
						<div class="order-item">
							<div class="item-number"><?= $i ?>.</div>
							<div class="item-title"><?= htmlspecialchars($item->getTitle()) ?></div>
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

	<div class="navigation">
		<a href="/admin/getOrders?page=<?= $currentPage - 1 ?>" class="navigation-page navigation-item
				<?= $currentPage === 1 ? 'navigation-blocked' : '' ?>"> < </a>
		<a href="/admin/getOrders?page=1" class="navigation-page navigation-item
				<?= $currentPage === 1 ? 'navigation-active' : '' ?>">1</a>

		<?php if ($pagesAmount > 7 && $currentPage >= 1 + 4): ?>
			<div class="navigation-dots navigation-item">···</div>
		<?php endif;?>

		<?php
		$startPage = 2;
		$endPage = 5;
		if ($currentPage >= 5)
		{
			$startPage = $currentPage - 1;
			$endPage = $currentPage + 1;
		}
		if ($currentPage > $pagesAmount - 4)
		{
			$startPage = $pagesAmount - 4;
			$endPage = $pagesAmount - 1;
		}
		if ($pagesAmount <= 7)
		{
			$startPage = 2;
			$endPage = $pagesAmount - 1;
		}
		for ($i = $startPage; $i <= $endPage; $i++): ?>
			<a href="/admin/getOrders?page=<?= $i ?>" class="navigation-page navigation-item
					<?= $currentPage === $i ? 'navigation-active' : '' ?>"> <?= $i ?> </a>
		<?php endfor;?>

		<?php if ($pagesAmount > 7 && $currentPage <= $pagesAmount - 4): ?>
			<div class="navigation-dots navigation-item">···</div>
		<?php endif;?>

		<?php if ($pagesAmount > 1): ?>
			<a href="/admin/getOrders?page=<?= $pagesAmount?>" class="navigation-page navigation-item
				<?= $currentPage === $pagesAmount ? 'navigation-active' : '' ?>"><?= $pagesAmount?></a>
		<?php endif;?>

		<a href="/admin/getOrders?page=<?= $currentPage + 1 ?>" class="navigation-page navigation-item
				<?= $currentPage === $pagesAmount ? 'navigation-blocked' : '' ?>"> > </a>
	</div>
</div>

<script src="/js/change-status.js"></script>
<script src="/js/admin-orders/manage-orders.js"></script>
