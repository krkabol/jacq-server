<?php

declare(strict_types=1);

namespace app\Core;

use Nette;
use Nette\Application\Routers\RouteList;


final class RouterFactory
{
	use Nette\StaticClass;

	public static function createRouter(): RouteList
	{
		$router = new RouteList;
		$router->addRoute('admin/<presenter>/<action>[/<id>]', 'Home:default');
		return $router;
	}
}
