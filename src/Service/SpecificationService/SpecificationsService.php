<?php

namespace Up\Service\SpecificationService;

interface SpecificationsService
{
	public function getItemTypes(): array;

	public function getCategories(): array;

	public function getSpecifications(): array;

	public function getItemTemplate(string $itemType): array;
}
