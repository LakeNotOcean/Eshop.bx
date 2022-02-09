<?php

/** Описание работы конфигурации DI-container'а
 * Конфигурация представляет собой массив %НазваниеЗависимости(класс)% => [конфигурация класса]
 * [конфигурация класса] также представляет собой массив со следующими доступными ключами:
 * 'classPath'* - название класса с полным неймспейсом(в php реализации конфигурации можно использовать ::class)
 * 'initType'* - тип инициализации. Может принимать значения {constructor|singleton}
 * 'initArgs'* - зависимости, нужные для создания класса.
 *                Указываются в виде ['типЗависимости', 'названиеЗависимости'].
 *                'типЗависимости' может принимать значения {class|var(обычная переменная)}
 *                В случае если зависимостей нет нужно указать пустой массив
 * 'initMethod' - если initType=singleton, то необходимо указать метод, вызываемый для инстанцирования класса
 */
$config = [
	'catalogController' => [
		'classPath' => \Up\Controller\CatalogController::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'templateProcessor'],
			['class', 'catalogService'],
		],
	],
	'addItemController' => [
		'classPath' => \Up\Controller\AddItemController::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'templateProcessor'],
			['class', 'specificationService']
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
			['class', 'specificationDAO'],
		],
	],
	'specificationService' => [
		'classPath' => \Up\Service\SpecificationsServiceHardcode::class,
		'initType' => 'constructor',
		'initArgs' => [],
	],
	'itemDAO' => [
		'classPath' => \Up\DAO\ItemDAOmysql::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'DB'],
		],
	],
	'specificationDAO' => [
		'classPath' => \Up\DAO\SpecificationDAO\SpecificationDAOmysql::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'DB'],
		],
	],
	'DB' => [
		'classPath' => \Up\Core\Database\DefaultDatabase::class,
		'initType' => 'singleton',
		'initArgs' => [],
		'initMethod' => 'getInstance',
	],
];