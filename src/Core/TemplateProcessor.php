<?php

namespace Up\Core;

class TemplateProcessor
{
	private static $viewPath = '../src/View/';

	public static function renderTemplate($templatePath, $templateParams): string
	{
		$fullTemplateName = self::$viewPath . $templatePath;
		if (file_exists($fullTemplateName))
		{
			ob_start();
			extract($templateParams, EXTR_OVERWRITE);

			require_once $fullTemplateName;

			return ob_get_clean();
		}

		return '';
	}

	public static function render($contentPath, $contentParams, $layoutPath, $layoutParams): string
	{
		$content = self::renderTemplate($contentPath, $contentParams);

		$params = array_merge($layoutParams, [
			'content' => $content,
		]);

		return self::renderTemplate($layoutPath, $params);
	}
}