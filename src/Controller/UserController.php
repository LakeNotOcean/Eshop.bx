<?php

namespace Up\Controller;

use Error;
use Exception;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\User\User;
use Up\Entity\User\UserEnum;
use Up\Entity\User\UserRole;
use Up\Validator\DataTypes;
use Up\Validator\Validator;
use Up\Service\UserService\UserServiceInterface;

class UserController
{
	protected $templateProcessor;
	protected $userServiceImpl;

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
		$errorString .= Validator::validate($firstName, DataTypes::names());
		$errorString .= Validator::validate($secondName, DataTypes::names());

		if ($errorString !== '')
		{
			throw new Error('некорректный формат полученный данных');
		}
		$user = new User($login, new UserRole(2, UserEnum::User()), $email, $phone, $firstName, $secondName);
		try
		{
			$this->userServiceImpl->registerUser($user, $password);
		}
		catch (Exception $e)
		{
			//todo - такой пользователь есть
			$page = $this->templateProcessor->render('register.php', ['state' => 'unsuccessful'], 'layout/main.php', []
			);
		}
		catch (Exception $e) //todo - для каждого случая свой exception
		{
			//todo - неверный пароль
			$page = $this->templateProcessor->render('register.php', ['state' => 'unsuccessful'], 'layout/main.php', []
			);
		}
		$page = $this->templateProcessor->render('register.php', ['state' => 'successful'], 'layout/main.php', []);
		$respons = new Response();

		return $respons->withBodyHTML($page);

	}

	public function loginUser(Request $request): Response
	{
		$login = $request->getPostParametersByName('login');
		$password = $request->getPostParametersByName('password');
		try
		{
			$this->userServiceImpl->authorizeUserByLogin($login, $password);
			header("Location: /");
		}
		catch (Exception $e)
		{
			//todo -  неверный пароль или логин
			$page = $this->templateProcessor->render('login.php', ['state' => 'unsuccessful'], 'layout/main.php', []);
		}

		$respons = new Response();

		return $respons->withBodyHTML($page);
	}

	public function loginUserPage(Request $request)
	{
		$page = $this->templateProcessor->render('login.php', ['state' => 'process'], 'layout/main.php', []);
		$respons = new Response();

		return $respons->withBodyHTML($page);
	}

	public function registerUserPage(Request $request)
	{
		$page = $this->templateProcessor->render('register.php', ['state' => 'process'], 'layout/main.php', []);
		$respons = new Response();

		return $respons->withBodyHTML($page);
	}

}
