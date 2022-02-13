<?php

namespace Up\Core\PHPDocParser;

interface PHPDocParserInterface
{
	public function setDocComment(string $docComment);
	public function get(string $annotation): string;
	public function getList(string $annotation): array;
}
