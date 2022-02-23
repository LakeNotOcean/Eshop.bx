<?php

namespace Up\Controller;

use Up\Core\Message\Request;
use Up\Core\Message\Response;
use Up\LayoutManager\MainLayoutManager;

class CoreController
{
	protected $mainLayoutManager;

	/**
	 * @param \Up\LayoutManager\MainLayoutManager $mainLayoutManager
	 */
	public function __construct(MainLayoutManager $mainLayoutManager)
	{
		$this->mainLayoutManager = $mainLayoutManager;
	}

	public function get404(Request $request)
	{
		return (new Response())->withBodyHTML(
			$this->mainLayoutManager->render('404.php', [])
		);
	}
}
