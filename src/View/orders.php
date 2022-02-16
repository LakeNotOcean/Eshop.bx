<?php

/** @var array $orders */

?>

<link rel="stylesheet" href="/css/orders.css">

<div class="container">

	<div class="order-list">
		<?php foreach ($orders as $order):?>
			<div class="order card">
				<?php var_dump($order);?>
			</div>
		<?php endforeach;?>
	</div>
</div>
