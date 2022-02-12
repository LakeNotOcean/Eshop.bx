<?php

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


$config = [
	TemplateProcessor::class => TemplateProcessorImpl::class,

	/*==========================
	Service
	==========================*/
	ImageServiceInterface::class => ImageService::class,
	ItemServiceInterface::class => ItemService::class,
	SpecificationsService::class => SpecificationsServiceImpl::class,
	TagService::class => TagServiceImpl::class,
	UserService::class => UserServiceImpl::class,

	/*==========================
	DAO
	==========================*/
	ItemDAO::class => ItemDAOmysql::class,
	SpecificationDAO::class => SpecificationDAOmysql::class,
	TagDAO::class => TagDAOmysql::class,
	UserDAO::class => UserDAOmysql::class,
];
