<?php

namespace Up\Core\Message;

class Header
{
	protected $name = '';
	protected $values = [];

	public function __construct(string $name, ...$values)
	{
		$this->values = $values;
		$this->name = $name;
	}

	public function addValue(...$values)
	{
		$this->values = array_unique(array_merge($values, $this->values));
	}

	public function getName() : string
	{
		return $this->name;
	}

	public function getValues() : array
	{
		return $this->values;
	}

	public function getValuesLine() : string
	{
		return implode(',', $this->values);
	}
}