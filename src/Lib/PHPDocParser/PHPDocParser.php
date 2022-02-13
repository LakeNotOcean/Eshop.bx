<?php

namespace Up\Lib\PHPDocParser;


class PHPDocParser implements PHPDocParserInterface
{
	private static $instance;
	protected $phpdoc;

	protected function __construct()
	{
		$this->phpdoc = [];
	}

	public static function getInstance(): PHPDocParser
	{
		if (!isset(static::$instance))
		{
			static::$instance = new static();
		}
		return static::$instance;
	}

	public function setDocComment(string $docComment): void
	{
		$this->phpdoc = [];
		foreach ($this->getAnnotations($docComment) as $annotation)
		{
			$annotationName = $this->getAnnotationName($annotation);
			$annotationValue = $this->getAnnotationValue($annotation);
			$this->phpdoc[$annotationName][] = $annotationValue;
		}
	}

	public function get(string $annotation): string
	{
		return $this->phpdoc[$annotation][0] ?? '';
	}

	public function getList(string $annotation): array
	{
		return $this->phpdoc[$annotation] ?? [];
	}

	protected function getAnnotations(string $docComment): array
	{
		preg_match_all('/@\w+ [\w\\\]+/', $docComment, $matches);
		return $matches[0];
	}

	protected function getAnnotationName(string $annotation): string
	{
		preg_match('/@\w+/', $annotation, $matches);
		return $matches[0];
	}

	protected function getAnnotationValue(string $annotation): string
	{
		return str_replace($this->getAnnotationName($annotation) . ' ', '', $annotation);
	}

}
