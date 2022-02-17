<?php

namespace Up\Entity\Order;

use Up\Core\Enum\Enum;


final class OrderStatus extends Enum
{
	private const IN_PROCESSING = 'IN_PROCESSING';
	private const DELIVERY = 'DELIVERY';
	private const DONE = 'DONE';
	private const CANCELLED = 'CANCELLED';

	public static function from(string $value): self
	{
		return parent::from($value);
	}

}
