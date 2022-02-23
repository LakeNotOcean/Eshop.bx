<?php

namespace Up\Lib\FormatHelper;


class DateFormatterRu
{
	public static function format(\DateTime $dateTime): string
	{
		$fmt = new \IntlDateFormatter(
			'ru-RU',
			\IntlDateFormatter::LONG,
			\IntlDateFormatter::NONE
		);
		return $fmt->format($dateTime);
	}
}