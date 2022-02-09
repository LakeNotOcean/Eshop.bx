<?php

namespace Up\Service\SpecificationService;


class SpecificationsServiceHardcode implements SpecificationsService
{
	public function getItemTypes(): array
	{
		return ['Компьютерные мыши', 'Видеокарты', 'Клавиатуры', 'Процессоры'];
	}

	public function getCategories(): array
	{
		return ['Заводские данные', 'Внешний вид'];
	}

	public function getSpecifications(): array
	{
		return ['Гарантия', 'Страна-производитель', 'Основной цвет', 'Дополнительный цвет', 'Подсветка'];
	}

	public function getItemTemplate(string $itemType): array
	{
		return ['Заводские данные'=>['Гарантия', 'Страна-производитель'], 'Внешний вид' => ['Основной цвет', 'Дополнительный цвет', 'Подсветка']];
	}
}
