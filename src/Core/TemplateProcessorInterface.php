<?php

namespace Up\Core;


interface TemplateProcessorInterface
{
	public function renderTemplate($templatePath, $templateParams): string;

	public function render($contentPath, $contentParams, $layoutPath, $layoutParams): string;
}
