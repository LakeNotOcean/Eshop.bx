<?php

$config = [
	'catalogController' => [
		'classPath' => \Up\Controller\CatalogController::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'templateProcessor'],
			['class', 'catalogService'],
		]
	],
	'templateProcessor' => [
		'classPath' => \Up\Core\TemplateProcessorImpl::class,
		'initType' => 'constructor',
		'initArgs' => [],
	],
	'catalogService' => [
		'classPath' => \Up\Service\CatalogServiceImpl::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'itemDAO'],
		],
	],
	'itemDAO' => [
		'classPath' => \Up\DAO\ItemDAOmysql::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'DB'],
		],
	],
	'DB' => [
		'classPath' => \Up\Core\DataBase\DefaultDatabase::class,
		'initType' => 'singleton',
		'initArgs' => [],
		'initMethod' => 'getInstance'
	],
];