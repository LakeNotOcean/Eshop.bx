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
use Up\Lib\Redirect;
use Up\Service\UserService\Error\UserServiceException;
use Up\Validator\DataTypes;
use Up\Validator\Validator;
use Up\Service\UserService\UserServiceInterface;


class UserController
{
	protected $templateProcessor;
	protected $userServiceImpl;
	public const nextUrlQueryKeyword = 'next';

	/**
	 * @param \Up\Core\TemplateProcessor $templateProcessor
	 * @param \Up\Service\UserService\UserService $userServiceImpl
	 */
	public function __construct(TemplateProcessorInterface $templateProcessor, UserServiceInterface $userServiceImpl)
	{
		$this->templateProcessor = $templateProcessor;
		$this->userServiceImpl = $userServiceImpl;
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

		$isAdmin = $request->getUser()->getRole()->getName() == UserEnum::Admin();

		if ($errorString !== '')
		{
			throw new Error($errorString);
		}
		$user = new User($login, new UserRole(UserEnum::User()), $email, $phone, $firstName, $secondName);
		try
		{
			$this->userServiceImpl->registerUser($user, $password);
		}
		catch (Exception $e)
		{
			$page = $this->templateProcessor->render('user.php', [], 'layout/main.php', [
				'isAuthenticated' => $this->userServiceImpl->isAuthenticated(),
				'isAdmin' => $isAdmin
			]);
			$response = new Response();
			$response = $response->withStatus(409);

			return $response->withBodyHTML($page);
		}
		$response = new Response();
		$page = $this->templateProcessor->render('user.php', [], 'layout/main.php', [
			'isAuthenticated' => $this->userServiceImpl->isAuthenticated(),
			'isAdmin' => $isAdmin
		]);

		return $response->withBodyHTML($page);
	}

	public function loginUser(Request $request): Response
	{
		$login = $request->getPostParametersByName('login');
		$password = $request->getPostParametersByName('password');

		$errorString = Validator::validate($login, DataTypes::login());
		$errorString .= Validator::validate($password, DataTypes::password());

		if ($errorString !== '')
		{
			return (new Response())->withStatus(409)->withBodyHTML($this->templateProcessor->render(
				'login.php', ['error' => $errorString],
				'layout/main.php', []
			));
		}

		try
		{
			$this->userServiceImpl->authorizeUserByLogin($login, $password);
		}
		catch (UserServiceException $e)
		{
			$isAdmin = $request->getUser()->getRole()->getName() == UserEnum::Admin();
			$page = $this->templateProcessor->render('login.php', [], 'layout/main.php', [
				'isAuthenticated' => $this->userServiceImpl->isAuthenticated(),
				'isAdmin' => $isAdmin
			]);
			$response = new Response();
			$response = $response->withStatus(409);

			return $response->withBodyHTML($page);
		}

		return Redirect::createResponseByURLName('home');
	}

	public function loginUserPage(Request $request)
	{
		$isAuthenticated = $request->getUser()->getRole()->getName() != UserEnum::Guest();
		$isAdmin = $request->getUser()->getRole()->getName() == UserEnum::Admin();

		$nextUrlParam = '';
		if ($request->containsQuery(static::nextUrlQueryKeyword))
		{
			$nextUrlParam = $request->getQueriesByName(static::nextUrlQueryKeyword);
		}

		if (!empty($nextUrlParam))
		{
			$nextUrlParam = '?' . static::nextUrlQueryKeyword . '=' . $nextUrlParam;
		}

		$page = $this->templateProcessor->render('login.php', [
			'state' => 'process', 'next' => $nextUrlParam
		], 'layout/main.php', [
			'isAuthenticated' => $isAuthenticated,
			'isAdmin' => $isAdmin
		]);

		return (new Response())->withBodyHTML($page);
	}

	public function registerUserPage(Request $request)
	{
		$isAdmin = $request->getUser()->getRole()->getName() == UserEnum::Admin();
		$page = $this->templateProcessor->render('register.php', [
			'state' => 'process'
		], 'layout/main.php', [
			'isAuthenticated' => $this->userServiceImpl->isAuthenticated(),
			'isAdmin' => $isAdmin
		]);
		$response = new Response();

		return $response->withBodyHTML($page);
	}

	/**
	 * @throws ResolveException
	 */
	public function logout(Request $request)
	{
		$this->userServiceImpl->removeUserFromSession();
		return Redirect::createResponseByURLName('home');
	}

}
