<?php

\Up\Core\Router\Router::post(
	"GET", "/user/profile/:user_id", // /user/profile/1
	[\Up\Controller\User::class, "view"]
);

\Up\Router::add("POST", "/user/profile",[\Up\Controller\User::class, "edit"]
);

\Up\Router::add(
	"GET", "/order/:id",
	[\Up\Controller\Order::class, "get"]
);

\Up\Router::add(
	"GET", "/order/view/:id",
	[\Up\Controller\Order::class, "view"]
);

\Up\Router::add("GET", "/test/:userId/:name",
	function ($userId, $name = 'asd') {
		return "Hello routing, userId: {$userId}. Your name is {$name}!";
	}
);
