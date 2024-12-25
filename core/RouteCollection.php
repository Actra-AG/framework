<?php
/**
 * @author    Christof Moser
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 */

namespace framework\core;

class RouteCollection
{
	/**
	 * @var Route[]
	 */
	private array $routes;

	public function __construct(array $routes = [])
	{
		foreach ($routes as $item) {
			$this->addRoute(route: $item);
		}
	}

	public function addRoute(Route $route): void
	{
		$this->routes[] = $route;
	}

	/**
	 * @return Route[]
	 */
	public function getRoutes(): array
	{
		return $this->routes;
	}

	public function hasRoutes(): bool
	{
		return (count(value: $this->routes) > 0);
	}

	public function getRouteForLanguage(string $languageCode): ?Route
	{
		foreach ($this->routes as $route) {
			if ($route->language->code === $languageCode) {
				return $route;
			}
		}

		return null;
	}

	public function getFirstRoute(): Route
	{
		return current(array: $this->routes);
	}
}