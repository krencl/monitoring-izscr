<?php

namespace App\Service;

use App\DTO\Route;
use Symfony\Component\HttpFoundation\Request;

final class Router
{
	/** @var array<Route> */
	private array $routes = [];

	public function __construct(private readonly Route $errorRoute)
	{
	}

	public function addRoute(Route $route): void
	{
		$this->routes[] = $route;
	}

	public function match(Request $request): Route
	{
		$path = $request->getPathInfo();

		foreach ($this->routes as $route) {
			if ($route->path === $path) {
				return $route;
			}
		}

		return $this->errorRoute;
	}
}
