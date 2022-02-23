<?php

namespace Up\Controller;

use Error;
use Exception;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Router\Error\ResolveException;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\User\User;
use Up\Entity\User\UserEnum;
use Up\Entity\User\UserRole;
use Up\LayoutManager\MainLayoutManager;
use Up\Lib\Redirect;
use Up\Lib\URLHelper;
use Up\Service\UserService\Error\UserServiceException;
use Up\Validator\DataTypes;
use Up\Validator\Validator;
use Up\Service\UserService\UserServiceInterface;


class UserController
{
	protected $templateProcessor;
	protected $mainLayoutManager;
	protected $userService;
	public const nextUrlQueryKeyword = 'next';

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\LayoutManager\MainLayoutManager $mainLayoutManager
	 * @param \Up\Service\UserService\UserService $userService
	 */
	public function __construct(
		TemplateProcessorInterface $templateProcessor,
		MainLayoutManager 		   $mainLayoutManager,
		UserServiceInterface 	   $userService)
	{
		$this->templateProcessor = $templateProcessor;
		$this->mainLayoutManager = $mainLayoutManager;
		$this->userService = $userService;
	}

	public function registerUser(Request $request): Response
	{
		$login = $request->getPostParametersByName('login');
		$phone = $request->getPostParametersByName('phone');
		$email = $request->getPostParametersByName('email');

		$password = $request->getPostParametersByName('password');
		$firstName = $request->getPostParametersByName('firstName');
		$secondName = $request->getPostParametersByName('secondName');

		$errorString = Validator::validate($email, DataTypes::email());
		$errorString .= Validator::validate($phone, DataTypes::phone());
		$errorString .= Validator::validate($login, DataTypes::login());
		$errorString .= Validator::validate($password, DataTypes::password());
		$errorString .= Validator::validate($firstName, DataTypes::names());
		$errorString .= Validator::validate($secondName, DataTypes::names());

		if ($errorString !== '')
		{
			throw new Error($errorString);
		}
		$user = new User(0, $login, new UserRole(UserEnum::User()), $email, $phone, $firstName, $secondName);
		try
		{
			$this->userService->registerUser($user, $password);
		}
		catch (Exception $e)
		{
			$page = $this->mainLayoutManager->render('register.php', []);
			$response = new Response();
			$response = $response->withStatus(409);

			return $response->withBodyHTML($page);
		}

		return Redirect::createResponseByURLName('home');
	}

	public function loginUser(Request $request): Response
	{
		$login = $request->getPostParametersByName('login');
		$password = $request->getPostParametersByName('password');

		$errorString = Validator::validate($login, DataTypes::login());
		$errorString .= Validator::validate($password, DataTypes::password());

		if ($errorString !== '')
		{
			return (new Response())->withStatus(409)->withBodyHTML(
				$this->mainLayoutManager->render('login.php', [
				'error' => $errorString
			]));
		}

		try
		{
			$this->userService->authorizeUserByLogin($login, $password);
		}
		catch (UserServiceException $e)
		{
			$page = $this->mainLayoutManager->render('login.php', []);
			$response = new Response();
			$response = $response->withStatus(409);

			return $response->withBodyHTML($page);
		}

		if ($request->containsQuery(static::nextUrlQueryKeyword))
		{
			$next = $request->getQueriesByName(static::nextUrlQueryKeyword);
			if (URLHelper::isValidUrl($next))
			{
				return Redirect::createResponseByURL($next);
			}
		}

		return Redirect::createResponseByURLName('home');
	}

	public function loginUserPage(Request $request): Response
	{
		$nextUrlParam = '';
		if ($request->containsQuery(static::nextUrlQueryKeyword))
		{
			$nextUrlParam = $request->getQueriesByName(static::nextUrlQueryKeyword);
		}

		if (!empty($nextUrlParam))
		{
			$nextUrlParam = '?' . static::nextUrlQueryKeyword . '=' . $nextUrlParam;
		}

		$page = $this->mainLayoutManager->render('login.php', [
			'state' => 'process',
			'next' => $nextUrlParam
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function registerUserPage(Request $request): Response
	{
		$page = $this->mainLayoutManager->render('register.php', [
			'state' => 'process'
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function getProfilePage(Request $request): Response
	{
		$page = $this->mainLayoutManager->render('user-profile.php', [
			'user' => $request->getUser()
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws UserServiceException
	 */
	public function adminListPage(Request $request): Response
	{
		if ($request->containsQuery("query"))
		{
			$login = $request->getQueriesByName("query");
			$adminList = $this->userService->getUserListByQuery('1',$login);
		}
		else
		{
			$adminList = $this->userService->getUserListByRole('1');
		}
		$currentPage = 1;
		$pagesAmount= 1;
		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);


		$page = $this->templateProcessor->render('add-admins.php', [
			'paginator' => $paginator,
			'admins' => $adminList,
		], 'layout/main.php', [
			'isAuthenticated' => $request->isAuthenticated(),
			'isAdmin' => $request->isAdmin(),
			'userName' => $request->getUser()->getName()
		]);

		return (new Response())->withBodyHTML($page);
	}


	public function removeAdmin(Request $request): Response
	{
		if ($request->containsPost("deleteAdmin"))
		{
			$login = $request->getPostParametersByName("deleteAdmin");
			$this->userService->removeUserModeratorRights($login);
		}
		$adminList = $this->userService->getUserListByRole('1');
		$currentPage = 1;
		$pagesAmount= 1;
		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);


		$page = $this->templateProcessor->render('add-admins.php', [
			'paginator' => $paginator,
			'admins' => $adminList,
		], 'layout/main.php', [
			'isAuthenticated' => $request->isAuthenticated(),
			'isAdmin' => $request->isAdmin(),
			'userName' => $request->getUser()->getName()
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws ResolveException
	 */
	public function logout(Request $request)
	{
		$this->userService->removeUserFromSession();
		return Redirect::createResponseByURLName('home');
	}

	public function updateUser(Request $request): Response
	{
		$firstName = $request->getUser()->getFirstName();
		$secondName = $request->getUser()->getSecondName();
		$phone = $request->getUser()->getPhone();
		$email = $request->getUser()->getEmail();

		if ($request->containsPost('user-first-name'))
		{
			$firstName = $request->getPostParametersByName('user-first-name');
		}
		if ($request->containsPost('user-second-name'))
		{
			$secondName = $request->getPostParametersByName('user-second-name');
		}
		if ($request->containsPost('user-phone'))
		{
			$phone = $request->getPostParametersByName('user-phone');
		}
		if ($request->containsPost('user-email'))
		{
			$email = $request->getPostParametersByName('user-email');
		}

		$user = $request->getUser();
		$user->setFirstName($firstName);
		$user->setSecondName($secondName);
		$user->setPhone($phone);
		$user->setEmail($email);

		$this->userService->updateUser($user);

		return (new Response())->withBodyHTML('');
	}

	public function changePasswordPage(Request $request)
	{
		return (new Response())->withBodyHTML(
			$this->mainLayoutManager->render('change-password.php', [])
		);
	}

	public function changePassword(Request $request)
	{
		if (!(
			$request->containsPost('oldPassword') ||
			$request->containsPost('newPassword1') ||
			$request->containsPost('newPassword2')
		))
		{
			return Redirect::createResponseByURLName('change-password');
		}
		$oldPassword = $request->getPostParametersByName('oldPassword');
		$newPassword1 = $request->getPostParametersByName('newPassword1');
		$newPassword2 = $request->getPostParametersByName('newPassword2');

		$validationErrors = [];
		if (!$this->userService->isValidPassword($oldPassword, $request->getUser()))
		{
			$validationErrors[] = 'Неверно введенный изменяемый пароль';
		}

		if (!($newPassword1 === $newPassword2))
		{
			$validationErrors[] = 'Новые пароли не совпадают';
		}
		$passwordValidationError = Validator::validate($newPassword1, DataTypes::password());

		if (!empty($passwordValidationError))
		{
			$validationErrors[] = 'Проблемы нового пароля: ' . $passwordValidationError;
		}

		if (!empty($validationErrors))
		{
			return (new Response())->withBodyHTML(
				$this->mainLayoutManager->render('change-password.php', [
					'errors' => $validationErrors
				])
			);
		}

		$this->userService->updatePassword($newPassword1, $request->getUser());

		return Redirect::createResponseByURLName('home');
	}

}
