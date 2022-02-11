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

use Up\Controller\AddItemController;
use Up\Controller\ItemController;
use Up\Controller\OrderController;
use Up\Controller\UserController;
use Up\Core\Database\DefaultDatabase;
use Up\Core\TemplateProcessorImpl;
use Up\DAO\ItemDAO\ItemDAOmysql;
use Up\DAO\SpecificationDAO\SpecificationDAOmysql;
use Up\DAO\TagDAO\TagDAOmysql;
use Up\DAO\UserDAO\UserDAOmysql;
use Up\Service\ImageService\ImageService;
use Up\Service\ItemService\ItemService;
use Up\Service\TagService\TagServiceImpl;

$config = [
	'templateProcessor' => [
		'classPath' => TemplateProcessorImpl::class,
		'initType' => 'constructor',
		'initArgs' => [],
	],

	/*==========================
	Controller
	==========================*/
	'itemController' => [
		'classPath' => ItemController::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'templateProcessor'],
			['class', 'itemService'],
			['class', 'imageService'],
		],
	],
	'addItemController' => [
		'classPath' => AddItemController::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'templateProcessor'],
			['class', 'specificationService'],
			['class', 'tagService'],
			['class', 'itemService'],
		],
	],
	'orderController' => [
		'classPath' => OrderController::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'templateProcessor'],
			['class', 'itemService'],
		],
	],
	'userController' => [
		'classPath' => UserController::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'templateProcessor'],
			['class', 'userService'],
		],
	],

	/*==========================
	Service
	==========================*/
	'itemService' => [
		'classPath' => ItemService::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'itemDAO'],
			['class', 'specificationDAO'],
		],
	],
	'imageService' => [
		'classPath' => ImageService::class,
		'initType' => 'constructor',
		'initArgs' => [],
	],
	'tagService' => [
		'classPath' => TagServiceImpl::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'tagDAO'],
		],
	],
	'specificationService' => [
		'classPath' => Up\Service\SpecificationService\SpecificationsServiceImpl::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'specificationDAO'],
		],
	],
	'userService' => [
		'classPath' => Up\Service\UserService\UserServiceImpl::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'userDAO'],
		],
	],

	/*==========================
	DAO and Database
	==========================*/
	'itemDAO' => [
		'classPath' => ItemDAOmysql::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'DB'],
		],
	],
	'specificationDAO' => [
		'classPath' => SpecificationDAOmysql::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'DB'],
		],
	],
	'userDAO' => [
		'classPath' => UserDAOmysql::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'DB'],
		],
	],
	'tagDAO' => [
		'classPath' => TagDAOmysql::class,
		'initType' => 'constructor',
		'initArgs' => [
			['class', 'DB'],
		],
	],
	'DB' => [
		'classPath' => DefaultDatabase::class,
		'initType' => 'singleton',
		'initArgs' => [],
		'initMethod' => 'getInstance',
	],

];
