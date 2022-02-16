<?php

namespace Up\Validator;

use ReflectionException;
use Up\Core\Enum\EnumException;

class Validator
{
	private const rules = [
		DataTypes::email => [
			ValidatorMethodEnum::emailFormat => [],
			ValidatorMethodEnum::maxLength => [20],
		],
		DataTypes::phone => [ValidatorMethodEnum::phoneFormat => []],
		DataTypes::login => [
			ValidatorMethodEnum::minLength => [3],
			ValidatorMethodEnum::maxLength => [20],
			ValidatorMethodEnum::onlyLatin => [],
		],
		DataTypes::password => [
			ValidatorMethodEnum::minLength => [5],
			ValidatorMethodEnum::maxLength => [50],
			ValidatorMethodEnum::onlyLatin => [],
		],
		DataTypes::names => [
			ValidatorMethodEnum::maxLength => [20],
			ValidatorMethodEnum::minLength=>[1],
			ValidatorMethodEnum::nameFormat => [],
		],
	];
	private const ValidatorPathMethod = "Up\Validator\Validator::";

	/** @param string|int $data
	 * @param DataTypes $dataType
	 *
	 * @throws EnumException
	 * @throws ReflectionException
	 */
	public static function validate($data, DataTypes $dataType): string
	{
		$errorString = '';
		$rule = self::rules[$dataType->getKey()];
		foreach ($rule as $method => $args)
		{
			array_unshift($args, $data);
			$classMethodName = self::ValidatorPathMethod . $method;
			$result = call_user_func_array($classMethodName, $args);
			if ($result !== '')
			{
				$errorString=$errorString.' '. $result;
			}
		}

		return $errorString;
	}

	private static function emailFormat(string $data): string
	{
		if (!filter_var($data, FILTER_VALIDATE_EMAIL))
		{
			return "wrong email format";
		}

		return '';
	}

	private static function maxLength(string $data, int $size): string
	{
		if (iconv_strlen($data) > $size)
		{
			return 'length is too long';
		}

		return '';
	}

	private static function minLength(string $data, int $size): string
	{
		if (iconv_strlen($data) < $size)
		{
			return 'length is too short';
		}

		return '';
	}

	private static function onlyLatin(string $data): string
	{
		$template = "/^[a-zA-Z0-9]+$/u";
		if (!preg_match($template, $data))
		{
			return "not only latin characters ";
		}

		return "";
	}

	private static function minMaxValueInt(int $data, int $minValue, int $maxValue): string
	{
		if ($data >= $maxValue && $data <= $maxValue)
		{
			return "the number is not in the range ";
		}

		return "";
	}

	private static function nameFormat(string $data): string
	{
		$template = "/^(?:\p{Cyrillic}+|\p{Latin}+)$/u";
		if (!preg_match($template, $data))
		{
			return "wrong name format ";
		}

		return "";
	}

	private static function numericFormat($data): string
	{
		if (!is_numeric($data))
		{
			return "not a numeric";
		}

		return "";
	}

	private static function phoneFormat($data): string
	{
		$template = "/^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/u";
		if (!preg_match($template, $data))
		{
			return "wrong phone format ";
		}

		return "";
	}

}