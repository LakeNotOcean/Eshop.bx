<?php

namespace Up\Core\Router;

class URLResolver
{
	/**
	 * @throws Error\ResolveException
	 */
	public static function resolve(string $urlName, array $urlParameters = []): string
	{
		$urlTemplate = Router::getInstance()->getUrlTemplateByName($urlName);
		$urlVariableNames = static::getURLVariableNames($urlTemplate);

		$expectedParametersCount = count($urlVariableNames);
		if (count($urlParameters) !== $expectedParametersCount)
		{
			$parametersCount = count($urlParameters);
			throw new Error\ResolveException(
				"Переданно неверное количество параметров урла. 
                                                Ожидалось: {$expectedParametersCount}.
                                                Передано: {$parametersCount} "
			);
		}

		return static::replaceURLVariablesFromUrlTemplate($urlTemplate, $urlVariableNames, $urlParameters);
	}

	private static function getURLVariableNames(string $urlTemplate): array
	{
		$matches = [];
		preg_match_all('/{(?<types>[\da-zA-Z]+):(?<variableNames>[\da-zA-Z]+)}/', $urlTemplate, $matches);

		return $matches['variableNames'];
	}

	/**
	 * @throws Error\ResolveException
	 */
	private static function replaceURLVariablesFromUrlTemplate(
		string $urlTemplate,
		array  $variableNames,
		array  $urlParameters
	): string
	{
		$result = $urlTemplate;
		foreach ($variableNames as $variableName)
		{
			if (!isset($urlParameters[$variableName]))
			{
				throw new Error\ResolveException("Нет параметра {$variableName} в переданных параметрах урла");
			}
			$result = preg_replace(
				"/{[\da-zA-Z]+:{$variableName}}/",
				$urlParameters[$variableName],
				$result
			);
		}

		return $result;
	}
}