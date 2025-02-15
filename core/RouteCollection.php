<?php
/**
 * @author    Christof Moser <contact@actra.ch>
 * @copyright Actra AG, Embrach, Switzerland, www.actra.ch
 * @license   MIT
 */

namespace framework\core;

class RouteCollection
{
    /**
     * @var Route[]
     */
    private(set) array $routes;

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

    public function hasRoutes(): bool
    {
        return (count(value: $this->routes) > 0);
    }

    public function getRouteForLanguage(string $languageCode): ?Route
    {
        return array_find($this->routes, fn($route) => $route->language->code === $languageCode);
    }

    public function getFirstRoute(): Route
    {
        return current(array: $this->routes);
    }
}