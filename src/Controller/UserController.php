<?php

namespace Up\Controller;

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
				$login,
				new UserRole(),
				$email,
				$phone);
			$this->userServiceImpl->registerUser($user,$password);
			$page = $this->templateProcessor->render('register.php',['state' => 'successful'] , 'main.php', []);
		}
		catch (\Exception $e)
		{
			$page = $this->templateProcessor->render('register.php',['state' => 'unsuccessful'] , 'main.php', []);
		}
		$respons = new Response();
		return $respons->withBodyHTML($page);

	}

	public function loginUserPage()
	{
		$page = $this->templateProcessor->render('login.php',['state' => 'process'],'main.php',[]);
		$respons = new Response();
		return $respons->withBodyHTML($page);
	}

	public function registerUserPage()
	{
		$page = $this->templateProcessor->render('register.php',['state' => 'process'],'main.php',[]);
		$respons = new Response();
		return $respons->withBodyHTML($page);
	}

	public function loginUser(Request $request):void
	{
		$login = $request->getPostParametersByName('login');
		$password = $request->getPostParametersByName('password');
	}
}