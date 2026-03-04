<?php

namespace config\Http;

final class Router
{
    /** @var Route[] */
    private array $routes = [];

    public function add(Route $route): void
    {
        $this->routes[] = $route;
    }

    public function dispatch(string $method, string $uri): array
    {
        foreach ($this->routes as $route) {

            $params = $route->match($method, $uri);

            if ($params !== null) {
                $params = array_map(function ($param) {
                    return ctype_digit($param) ? $param : $param;
                }, $params);

                return [
                    'controller' => $route->controller,
                    'action' => $route->action,
                    'params' => $params
                ];
            }
        }

        throw new \Exception('Route not found');
    }
}
