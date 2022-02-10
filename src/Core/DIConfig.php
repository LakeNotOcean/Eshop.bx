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
	'templateProcessor' => [
		'classPath' => \Up\Core\TemplateProcessorImpl::class,
		'initType' => 'constructor',
		'initArgs' => [],
	],

	/*==========================
	Controller
	==========================*/
	'itemController' => [
		'classPath' => \Up\Controller\ItemController::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'templateProcessor'],
			['class', 'itemService'],
			['class', 'imageService']
		],
	],
	'addItemController' => [
		'classPath' => \Up\Controller\AddItemController::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'templateProcessor'],
			['class', 'specificationService'],
			['class','']
		],
	],
	'orderController' => [
		'classPath' => \Up\Controller\OrderController::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'templateProcessor'],
			['class', 'itemService']
		],
	],

	/*==========================
	Service
	==========================*/
	'itemService' => [
		'classPath' => \Up\Service\ItemService\ItemService::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'itemDAO'],
			['class', 'specificationDAO'],
		],
	],
	'imageService' => [
		'classPath' => \Up\Service\ImageService\ImageService::class,
		'initType' => 'constructor',
		'initArgs' => [],
	],
	'specificationService' => [
		'classPath' => Up\Service\SpecificationService\SpecificationsServiceImpl::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'specificationDAO'],
		],
	],
	'userService'=>[
		'classPath'=>\Up\Service\UserService\UserServiceImpl::class,
		'initType'=>'constructor',
		'initArgs'=>[
			['class','userDAO'],
		],
	],
	'userDAO'=>[
		'classPath'=>\Up\DAO\UserDAO\UserDAOmysql::class,
		'initType'=>'constructor',
		'initArgs' => [
			['class', 'DB'],
		],
	],
	'itemDAO' => [
		'classPath' => \Up\DAO\ItemDAO\ItemDAOmysql::class,
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