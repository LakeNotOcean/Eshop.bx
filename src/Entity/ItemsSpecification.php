<?php

namespace Up\Entity;

class ItemsSpecification
{
	private $manufacturer = '';
	private $country = '';
	private $warranty = 0;
	private $releaseYear = 0;
	private $memorySize = 0;
	private $memoryType = '';
	private $busWidth = 0;
	private $technicalProcess = 0;
	private $chipFrequency = 0;
	private $memoryFrequency = 0;
	private $maxResolution = '';
	private $outConnectors = '';
	private $interface = '';
	private $additionalPower = '';
	private $requiredPower = 0;
	private $fansNum = '';
	private $length = 0;
	private $thickness = 0;

	/**
	 * @return string
	 */
	public function getManufacturer(): string
	{
		return $this->manufacturer;
	}

	/**
	 * @param string $manufacturer
	 */
	public function setManufacturer(string $manufacturer): void
	{
		$this->manufacturer = $manufacturer;
	}

	/**
	 * @return string
	 */
	public function getCountry(): string
	{
		return $this->country;
	}

	/**
	 * @param string $country
	 */
	public function setCountry(string $country): void
	{
		$this->country = $country;
	}

	/**
	 * @return int
	 */
	public function getWarranty(): int
	{
		return $this->warranty;
	}

	/**
	 * @param int $warranty
	 */
	public function setWarranty(int $warranty): void
	{
		$this->warranty = $warranty;
	}

	/**
	 * @return int
	 */
	public function getReleaseYear(): int
	{
		return $this->releaseYear;
	}

	/**
	 * @param int $releaseYear
	 */
	public function setReleaseYear(int $releaseYear): void
	{
		$this->releaseYear = $releaseYear;
	}

	/**
	 * @return int
	 */
	public function getMemorySize(): int
	{
		return $this->memorySize;
	}

	/**
	 * @param int $memorySize
	 */
	public function setMemorySize(int $memorySize): void
	{
		$this->memorySize = $memorySize;
	}

	/**
	 * @return string
	 */
	public function getMemoryType(): string
	{
		return $this->memoryType;
	}

	/**
	 * @param string $memoryType
	 */
	public function setMemoryType(string $memoryType): void
	{
		$this->memoryType = $memoryType;
	}

	/**
	 * @return int
	 */
	public function getBusWidth(): int
	{
		return $this->busWidth;
	}

	/**
	 * @param int $busWidth
	 */
	public function setBusWidth(int $busWidth): void
	{
		$this->busWidth = $busWidth;
	}

	/**
	 * @return int
	 */
	public function getTechnicalProcess(): int
	{
		return $this->technicalProcess;
	}

	/**
	 * @param int $technicalProcess
	 */
	public function setTechnicalProcess(int $technicalProcess): void
	{
		$this->technicalProcess = $technicalProcess;
	}

	/**
	 * @return int
	 */
	public function getChipFrequency(): int
	{
		return $this->chipFrequency;
	}

	/**
	 * @param int $chipFrequency
	 */
	public function setChipFrequency(int $chipFrequency): void
	{
		$this->chipFrequency = $chipFrequency;
	}

	/**
	 * @return string
	 */
	public function getMaxResolution(): string
	{
		return $this->maxResolution;
	}

	/**
	 * @param string $maxResolution
	 */
	public function setMaxResolution(string $maxResolution): void
	{
		$this->maxResolution = $maxResolution;
	}

	/**
	 * @return string
	 */
	public function getOutConnectors(): string
	{
		return $this->outConnectors;
	}

	/**
	 * @param string $outConnectors
	 */
	public function setOutConnectors(string $outConnectors): void
	{
		$this->outConnectors = $outConnectors;
	}

	/**
	 * @return string
	 */
	public function getInterface(): string
	{
		return $this->interface;
	}

	/**
	 * @param string $interface
	 */
	public function setInterface(string $interface): void
	{
		$this->interface = $interface;
	}

	/**
	 * @return string
	 */
	public function getAdditionalPower(): string
	{
		return $this->additionalPower;
	}

	/**
	 * @param string $additionalPower
	 */
	public function setAdditionalPower(string $additionalPower): void
	{
		$this->additionalPower = $additionalPower;
	}

	/**
	 * @return int
	 */
	public function getRequiredPower(): int
	{
		return $this->requiredPower;
	}

	/**
	 * @param int $requiredPower
	 */
	public function setRequiredPower(int $requiredPower): void
	{
		$this->requiredPower = $requiredPower;
	}

	/**
	 * @return string
	 */
	public function getFansNum(): string
	{
		return $this->fansNum;
	}

	/**
	 * @param string $fansNum
	 */
	public function setFansNum(string $fansNum): void
	{
		$this->fansNum = $fansNum;
	}

	/**
	 * @return int
	 */
	public function getLength(): int
	{
		return $this->length;
	}

	/**
	 * @param int $length
	 */
	public function setLength(int $length): void
	{
		$this->length = $length;
	}

	/**
	 * @return int
	 */
	public function getThickness(): int
	{
		return $this->thickness;
	}

	/**
	 * @param int $thickness
	 */
	public function setThickness(int $thickness): void
	{
		$this->thickness = $thickness;
	}

	/**
	 * @return int
	 */
	public function getMemoryFrequency(): int
	{
		return $this->memoryFrequency;
	}

	/**
	 * @param int $memoryFrequency
	 */
	public function setMemoryFrequency(int $memoryFrequency): void
	{
		$this->memoryFrequency = $memoryFrequency;
	}
}