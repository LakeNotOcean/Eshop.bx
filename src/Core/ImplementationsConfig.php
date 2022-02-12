<?php

use Up\Controller\AddItemController;
use Up\Controller\ItemController;
use Up\Controller\OrderController;
use Up\Controller\UserController;
use Up\Core\Database\DefaultDatabase;
use Up\Service\UserService\UserServiceImpl;
use Up\Service\SpecificationService\SpecificationsServiceImpl;
use Up\DAO\UserDAO\UserDAO;
use Up\DAO\TagDAO\TagDAO;
use Up\DAO\SpecificationDAO\SpecificationDAO;
use Up\DAO\ItemDAO\ItemDAO;
use Up\Service\UserService\UserService;
use Up\Service\TagService\TagService;
use Up\Service\SpecificationService\SpecificationsService;
use Up\Service\ItemService\ItemServiceInterface;
use Up\Service\ImageService\ImageServiceInterface;
use Up\Core\TemplateProcessor;
use Up\Core\TemplateProcessorImpl;
use Up\DAO\ItemDAO\ItemDAOmysql;
use Up\DAO\SpecificationDAO\SpecificationDAOmysql;
use Up\DAO\TagDAO\TagDAOmysql;
use Up\DAO\UserDAO\UserDAOmysql;
use Up\Service\ImageService\ImageService;
use Up\Service\ItemService\ItemService;
use Up\Service\TagService\TagServiceImpl;


$implementations = [
	/*==========================
	Controllers
	==========================*/
	AddItemController::class => [
		TemplateProcessor::class => TemplateProcessorImpl::class,
		SpecificationsService::class => SpecificationsServiceImpl::class,
		TagService::class => TagServiceImpl::class,
		ItemServiceInterface::class => ItemService::class,
	],
	ItemController::class => [
		TemplateProcessor::class => TemplateProcessorImpl::class,
		ItemServiceInterface::class => ItemService::class,
		ImageServiceInterface::class => ImageService::class,
	],
	OrderController::class => [
		TemplateProcessor::class => TemplateProcessorImpl::class,
		ItemServiceInterface::class => ItemService::class,
	],
	UserController::class => [
		TemplateProcessor::class => TemplateProcessorImpl::class,
		UserService::class => UserServiceImpl::class,
	],

	/*==========================
	Services
	==========================*/
	ItemService::class => [
		ItemDAO::class => ItemDAOmysql::class,
		SpecificationDAO::class => SpecificationDAOmysql::class,
	],
	SpecificationsServiceImpl::class => [
		SpecificationDAO::class => SpecificationDAOmysql::class,
	],
	TagServiceImpl::class => [
		TagDAO::class => TagDAOmysql::class,
	],
	UserServiceImpl::class => [
		UserDAO::class => UserDAOmysql::class,
	],

	/*==========================
	DAO
	==========================*/
	ItemDAOmysql::class => [
		DefaultDatabase::class => DefaultDatabase::class
	],
	SpecificationDAOmysql::class => [
		DefaultDatabase::class => DefaultDatabase::class
	],
	TagDAOmysql::class => [
		DefaultDatabase::class => DefaultDatabase::class
	],
	UserDAOmysql::class => [
		DefaultDatabase::class => DefaultDatabase::class
	],
];
