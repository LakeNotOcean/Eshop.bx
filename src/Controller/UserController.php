<?php

namespace Up\Controller;


use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\Router\Error\ResolveException;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\User\User;
use Up\Entity\User\UserEnum;
use Up\Entity\User\UserRole;
use Up\LayoutManager\MainLayoutManager;
use Up\Lib\Paginator\Paginator;
use Up\Lib\Redirect;
use Up\Lib\URLHelper;
use Up\Service\UserService\Error\UserServiceException;
use Up\Validator\DataTypes;
use Up\Validator\Validator;
use Up\Service\UserService\UserServiceInterface;
use Validator\ValidationException;

class UserController
{
	protected $templateProcessor;
	protected $mainLayoutManager;
	protected $userService;
	public const nextUrlQueryKeyword = 'next';
	protected $adminsInPage = 10;
	protected $usersInPage = 9;

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

	/**
	 * @throws \Up\Validator\ValidationException
	 * @throws ResolveException
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
	public function registerUser(Request $request): Response
	{
		$login = $request->getPostParametersByName('login');
		$phone = $request->getPostParametersByName('phone');
		$email = $request->getPostParametersByName('email');

		$password = $request->getPostParametersByName('password');
		$firstName = $request->getPostParametersByName('firstName');
		$secondName = $request->getPostParametersByName('secondName');
		$user = new User(0, $login, new UserRole(UserEnum::User()), $email, $phone, $firstName, $secondName);
		$this->userService->registerUser($user, $password);

		return Redirect::createResponseByURLName('set-type-item');
	}

	/**
	 * @throws UserServiceException
	 * @throws ResolveException
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
	public function loginUser(Request $request): Response
	{
		$login = $request->getPostParametersByName('login');
		$password = $request->getPostParametersByName('password');
		$this->userService->authorizeUserByLogin($login, $password);
		if ($request->containsQuery(static::nextUrlQueryKeyword))
		{
			$next = $request->getQueriesByName(static::nextUrlQueryKeyword);
			if (URLHelper::isValidUrl($next))
			{
				return Redirect::createResponseByURL($next);
			}
		}

		return Redirect::createResponseByURLName('set-type-item');
	}

	/**
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
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

		$page = $this->mainLayoutManager->render('user/sign-in.php', [
			'state' => 'process',
			'next' => $nextUrlParam
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function registerUserPage(Request $request): Response
	{
		$page = $this->mainLayoutManager->render('user/sign-up.php', [
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
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 * @throws UserServiceException
	 */
	public function userListPage(Request $request): Response
	{
		$currentPage = ($request->containsQuery('page')) ? (int) $request->getQueriesByName('page') : 1;
		$currentPage = ($currentPage > 0) ? $currentPage : 1;
		$query = $request->getQueriesOrDefaultList(['query'=>'']);
		$search = $query['query'];
		$itemsAmount = $this->userService->getAmountUserByQuery(0,$search);
		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->usersInPage);
		$userList = $this->userService->getUserListByQuery(Paginator::getLimitOffset($currentPage,$this->usersInPage),0,$search);

		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);

		$page = $this->mainLayoutManager->render('admin/user-list.php', [
			'userAmount' => $itemsAmount,
			'paginator' => $paginator,
			'users' => $userList,
			'query' => $search,
		]);

		return (new Response())->withBodyHTML($page);
	}



	/**
	 * @throws \ReflectionException
	 * @throws \Up\Core\Enum\EnumException
	 * @throws UserServiceException
	 */
	public function userInfoPage(Request $request,int $id):Response
	{
		$user = $this->userService->getUserInfoById($id);
		$roles = $this->userService->getAllRoles();
		$page = $this->mainLayoutManager->render('user-profile.php', [
			'user' => $user,
			'fromUserList' => true,
			'roles' => $roles,
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws \ReflectionException
	 * @throws \Up\Core\Enum\EnumException
	 * @throws UserServiceException
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
	public function adminUpdateUser(Request $request, int $id):Response
	{
		$user = $this->userService->getUserInfoById($id);
		if ($request->containsPost('user-first-name'))
		{
			$firstName = $request->getPostParametersByName('user-first-name');
			$user->setFirstName($firstName);
		}
		if ($request->containsPost('user-second-name'))
		{
			$secondName = $request->getPostParametersByName('user-second-name');
			$user->setSecondName($secondName);
		}
		if ($request->containsPost('user-phone')
			&& Validator::validate($request->getPostParametersByName('user-email'), new DataTypes("phone")))
		{
			$phone = $request->getPostParametersByName('user-phone');
			$user->setPhone($phone);
		}
		if (
			$request->containsPost('user-email')
			&& Validator::validate($request->getPostParametersByName('user-email'), new DataTypes("email"))
		)
		{
			$email = $request->getPostParametersByName('user-email');
			$user->setEmail($email);
		}
		if ($request->containsPost('user-role'))
		{
			$role = $request->getPostParametersByName('user-role');
			$this->userService->changeUserRoleByLogin($user->getLogin(),$role);
		}

		$this->userService->updateUser($user);

		return (new Response())->withBodyHTML('');

	}

	/**
	 * @throws UserServiceException
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
	public function adminListPage(Request $request): Response
	{
		$currentPage = ($request->containsQuery('page')) ? (int) $request->getQueriesByName('page') : 1;
		$currentPage = ($currentPage > 0) ? $currentPage : 1;
		$query = $request->getQueriesOrDefaultList(['query'=>'']);
		$search = $query['query'];
		$itemsAmount = $this->userService->getAmountUserByQuery(1,$search);
		$pagesAmount = Paginator::getPageCount($itemsAmount, $this->adminsInPage);
		$adminList = $this->userService->getUserListByQuery(Paginator::getLimitOffset($currentPage,$this->adminsInPage),1,$search);

		$paginator = $this->templateProcessor->renderTemplate('block/paginator.php', [
			'currentPage' => $currentPage,
			'pagesAmount' => $pagesAmount,
		]);

		$page = $this->mainLayoutManager->render('admin/admins-list.php', [
			'paginator' => $paginator,
			'admins' => $adminList,
			'query' => $search,
			'login' => $request->getUser()->getLogin(),
		]);

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws UserServiceException
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
	public function removeAdmin(Request $request): Response
	{
		if ($request->containsPost("deleteAdmin"))
		{
			$login = $request->getPostParametersByName("deleteAdmin");
			$this->userService->removeUserModeratorRights($login);
		}
		$page = 'true';

		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 * @throws UserServiceException
	 */
	public function addAdmin(Request $request):Response
	{
		if ($request->containsPost("addAdmin"))
		{
			$login = $request->getPostParametersByName("addAdmin");
			$this->userService->giveUserAdministratorRoleByLogin($login);
		}
		$page = 'true';
		return (new Response())->withBodyHTML($page);
	}

	/**
	 * @throws ResolveException
	 */
	public function logout(Request $request)
	{
		$this->userService->removeUserFromSession();
		return Redirect::createResponseByURLName('set-type-item');
	}

	/**
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
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
		if (
			$request->containsPost('user-email')
			&& preg_match("/^[^@\s]+@[^@\s]+\.[^@\s]+$/", $request->getPostParametersByName('user-email'))
		)
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

	public function changePasswordPage(Request $request): Response
	{
		return (new Response())->withBodyHTML(
			$this->mainLayoutManager->render('user/change-password.php', [])
		);
	}

	/**
	 * @throws ResolveException
	 * @throws \Up\Core\Message\Error\NoSuchQueryParameterException
	 */
	public function changePassword(Request $request): Response
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
			$validationErrors[] = '?????????????? ?????????????????? ???????????????????? ????????????';
		}

		if (!($newPassword1 === $newPassword2))
		{
			$validationErrors[] = '?????????? ???????????? ???? ??????????????????';
		}
		$passwordValidationError = Validator::validate($newPassword1, DataTypes::password());

		if (!empty($passwordValidationError))
		{
			foreach ($passwordValidationError as $error)
			{
				$validationErrors[] = '???????????????? ???????????? ????????????: ' . $error;
			}
		}

		if (!empty($validationErrors))
		{
			return (new Response())->withBodyHTML(
				$this->mainLayoutManager->render('user/change-password.php', [
					'errors' => $validationErrors
				])
			);
		}

		$this->userService->updatePassword($newPassword1, $request->getUser());

		return Redirect::createResponseByURLName('home');
	}

}
