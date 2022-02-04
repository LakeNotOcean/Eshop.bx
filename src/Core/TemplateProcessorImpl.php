<?php

namespace Up\Core;

class TemplateProcessorImpl implements TemplateProcessor
{
	private static $viewPath = '../src/View/';

	public function renderTemplate($templatePath, $templateParams): string
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

	public function render($contentPath, $contentParams, $layoutPath, $layoutParams): string
	{
		$content = $this->renderTemplate($contentPath, $contentParams);

		$params = array_merge($layoutParams, [
			'content' => $content,
		]);

		return $this->renderTemplate($layoutPath, $params);
	}
}