<?php

namespace Up\Validator;

use ReflectionException;
use Up\Core\Enum\EnumException;

class Validator
{
	private const rules = [
		DataTypes::email => [
			ValidatorMethodEnum::emailFormat => [],
			ValidatorMethodEnum::maxLength => [127],
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
		DataTypes::rating =>[
			ValidatorMethodEnum::minMaxValueInt => [0, 5],
		],
		DataTypes::reviewText=>[
			ValidatorMethodEnum::minLength => [20],
			ValidatorMethodEnum::maxLength => [1000],
		],
	];
	private const ValidatorPathMethod = "Up\Validator\Validator::";

	/**
	 * @param string|int $data
	 * @param DataTypes $dataType
	 *
	 * @return array<string>
	 */
	public static function validate($data, DataTypes $dataType): array
	{
		$errors = [];
		$rule = self::rules[$dataType->getKey()];
		foreach ($rule as $method => $args)
		{
			array_unshift($args, $data);
			$classMethodName = self::ValidatorPathMethod . $method;
			$result = call_user_func_array($classMethodName, $args);
			if ($result !== '')
			{
				$errors[] = $result;
			}
		}

		return $errors;
	}

	/**
	 * @param array<string, array> $fields массив соответствий групп ошибок и массивов (0)поля, (1)правила валдиации, (2)префикс ошибки при выводе
	 * $fields = [
	 * 		'errorGroup1' => [field, (DataTypes), errorTextPrefix],
	 * 		'errorGroup2' => [field, (DataTypes), errorTextPrefix]
	 * ]
	 * example:
	 * $fields = [
	 * 		'rating' => [$review->getRating(), DataTypes::rating(), "Ошибка в оценке: "],
	 * 		'text_review' => [$review->getComment(), DataTypes::reviewText(), "Ошибка в тексте отзыва: "],
	 * ];
	 *
	 * @return array
	 *
	 */
	public static function validateFields(array $fields): array
	{
		$result = [];
		foreach ($fields as $field => $vaildatorInfo)
		{
			$validateErrors = Validator::validate($vaildatorInfo[0], $vaildatorInfo[1]);
			foreach ($validateErrors as $error)
			{
				$result[$field][] = $vaildatorInfo[2] . $error;
			}
		}
		return $result;
	}

	private static function emailFormat(string $data): string
	{
		if (!filter_var($data, FILTER_VALIDATE_EMAIL))
		{
			return "Неправильный email формат";
		}

		return '';
	}

	private static function maxLength(string $data, int $size): string
	{
		$dataSize = iconv_strlen($data);
		if ($dataSize > $size)
		{
			return "Слишком много символов. Вы ввели: {$dataSize}. Необходимо: {$size}";
		}

		return '';
	}

	private static function minLength(string $data, int $size): string
	{
		$dataSize = iconv_strlen($data);
		if ($dataSize < $size)
		{
			return "Слишком мало символов. Вы ввели: {$dataSize}. Необходимо: {$size}";
		}

		return '';
	}

	private static function onlyLatin(string $data): string
	{
		$template = "/^[a-zA-Z0-9]+$/u";
		if (!preg_match($template, $data))
		{
			return "Должны быть только латинские символы";
		}

		return "";
	}

	private static function minMaxValueInt(int $data, int $minValue, int $maxValue): string
	{
		if (!($data <= $maxValue && $data >= $minValue))
		{
			return "Число должно входить в промежуток от $minValue до $maxValue";
		}

		return "";
	}

	private static function nameFormat(string $data): string
	{
		$template = "/^(?:\p{Cyrillic}+|\p{Latin}+)$/u";
		if (!preg_match($template, $data))
		{
			return "Неверный формат имени.";
		}

		return "";
	}

	private static function numericFormat($data): string
	{
		if (!is_numeric($data))
		{
			return "Должно быть числом";
		}

		return "";
	}

	private static function phoneFormat($data): string
	{
		$template = "/^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$/u";
		if (!preg_match($template, $data))
		{
			return "Неверный фомат телефонного номера";
		}

		return "";
	}

}