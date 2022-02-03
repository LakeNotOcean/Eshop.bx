<?php

namespace Up\Core;

class TemplateProcessor
{
	public $viewPath = '../src/View/';

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

		return self::renderTemplate($layoutPath, [
			'content' => $content,
			'params' => $layoutParams]);
	}
}