<?php

namespace Up\Controller;

use Exception;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessorInterface;
use Up\Entity\User;
use Up\Entity\UserRole;
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
		$error=[];
		$password = $request->getPostParametersByName('password');
		$error[]=Validator::validate($email,DataTypes::email);
		$error[]=Validator::validate($phone,DataTypes::phone);
		$error[]=Validator::validate($login,DataTypes::login);
		$error[]=Validator::validate($email,DataTypes::email);
		try
		{
			$user = new User(
				$login, new UserRole(), $email, $phone
			);
		}
		catch (Exception $e)
		{
			//todo - неверное поле, указать неверное поле.
			$page = $this->templateProcessor->render('register.php', ['state' => 'unsuccessful'], 'main.php', []);
		}
		try
		{
			$this->userServiceImpl->registerUser($user, $password);
		}
		catch (Exception $e)
		{
			//todo - такой пользователь есть
			$page = $this->templateProcessor->render('register.php', ['state' => 'unsuccessful'], 'layout/main.php', []);
		}
		catch (Exception $e) //todo - для каждого случая свой exception
		{
			//todo - неверный пароль
			$page = $this->templateProcessor->render('register.php', ['state' => 'unsuccessful'], 'layout/main.php', []);
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

	public function loginUserPage()
	{
		$page = $this->templateProcessor->render('login.php', ['state' => 'process'], 'layout/main.php', []);
		$respons = new Response();

		return $respons->withBodyHTML($page);
	}

	public function registerUserPage()
	{
		$page = $this->templateProcessor->render('register.php', ['state' => 'process'], 'layout/main.php', []);
		$respons = new Response();

		return $respons->withBodyHTML($page);
	}

}
