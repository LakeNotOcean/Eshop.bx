<?php

namespace Up\Core;

class TemplateProcessor
{
	public static function renderTemplate($templatePath, $templateParams): string
	{
		if (file_exists($templatePath))
		{
			ob_start();
			extract($templateParams, EXTR_OVERWRITE);

			require_once $templatePath;

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