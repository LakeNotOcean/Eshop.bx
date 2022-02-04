<?php

namespace Up\Model;

class Item
{
	private int $id;
	private string $title;
	private float $price;
	private string $shortDesc;
	private string $fullDesc;
	private array $specs;

	public function __construct(array $itemData)
	{
		$this->id = $itemData['ID'];
		$this->title = $itemData['TITLE'];
		$this->price = $itemData['PRICE'];
		$this->shortDesc = $itemData['SHORT_DESC'];
		$this->fullDesc = $itemData['FULL_DESC'];
		$this->specs = $itemData['SPECS'];
	}

	public function getId(): int
	{
		return $this->id;
	}

	public function getTitle(): string
	{
		return $this->title;
	}

	public function getPrice(): float
	{
		return $this->price;
	}

	public function getFormattedPrice(): string
	{
		$price = $this->getPrice();
		$decimals = $price - floor($price);
		return number_format($price, $decimals ? 2 : 0, '.', ' ');
	}

	public function getShortDesc(): string
	{
		return $this->shortDesc;
	}

	public function getFullDesc(): string
	{
		return $this->fullDesc;
	}

	public function getSpecs(): array
	{
		return $this->specs;
	}

}
