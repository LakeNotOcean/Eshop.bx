<?php

namespace Up\Core\DI;

class DIConfigPHP implements DIConfigInterface
{
	public function getConfig(string $path): array
	{
		return [
			'catalogController' => [
				'classPath' => 'Up\Controller\CatalogController',
				'initType' => 'constructor',
				'initArgs' => [
					['class', 'templateProcessor'],
					['class', 'catalogService'],
				]
			],
			'templateProcessor' => [
				'classPath' => 'Up\Core\TemplateProcessorImpl',
				'initType' => 'constructor',
				'initArgs' => [],
			],
			'catalogService' => [
				'classPath' => 'Up\Service\CatalogServiceImpl',
				'initType' => 'constructor',
				'initArgs' => [
					['class', 'itemDAO'],
				],
			],
			'itemDAO' => [
				'classPath' => 'Up\DAO\ItemDAOmysql',
				'initType' => 'constructor',
				'initArgs' => [
					['class', 'DB'],
				],
			],
			'DB' => [
				'classPath' => 'Up\Core\DataBase\DefaultDatabase',
				'initType' => 'singleton',
				'initArgs' => [],
				'initMethod' => 'getInstance'
			],
		];
	}
}