<?php

namespace Up\Service\SpecificationService;

interface SpecificationsService
{
	public function getItemTypes(): array;

	public function getCategories(): array;

	public function getItemTemplate(int $templateId): array;

	public function specificationsSort(array &$categories): void;
}
