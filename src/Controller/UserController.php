<?php

namespace Up\Controller;

use Exception;
use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\Core\TemplateProcessor;
use Up\Entity\User;
use Up\Entity\UserRole;
use Up\Service\UserService\userServiceImpl;

class UserController
{
	protected $templateProcessor;
	protected $userServiceImpl;

	public function __construct(TemplateProcessor $templateProcessor, userServiceImpl $userServiceImpl)
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
			$page = $this->templateProcessor->render('register.php', ['state' => 'unsuccessful'], 'main.php', []);
		}
		catch (Exception $e) //todo - для каждого случая свой exception
		{
			//todo - неверный пароль
			$page = $this->templateProcessor->render('register.php', ['state' => 'unsuccessful'], 'main.php', []);
		}
		$page = $this->templateProcessor->render('register.php', ['state' => 'successful'], 'main.php', []);
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
			$page = $this->templateProcessor->render('login.php', ['state' => 'unsuccessful'], 'main.php', []);
		}

		$respons = new Response();

		return $respons->withBodyHTML($page);
	}

	public function loginUserPage()
	{
		$page = $this->templateProcessor->render('login.php', ['state' => 'process'], 'main.php', []);
		$respons = new Response();

		return $respons->withBodyHTML($page);
	}

	public function registerUserPage()
	{
		$page = $this->templateProcessor->render('register.php', ['state' => 'process'], 'main.php', []);
		$respons = new Response();

		return $respons->withBodyHTML($page);
	}

}