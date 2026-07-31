<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $uri, callable|array $action): void
    {
        $this->routes['GET'][$uri] = $action;
    }

    public function post(string $uri, callable|array $action): void
    {
        $this->routes['POST'][$uri] = $action;
    }

    public function dispatch(): void
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        $basePath = '/incuyo/cyberblog/public';

        if (str_starts_with($requestUri, $basePath)) {
            $requestUri = substr($requestUri, strlen($basePath));
        }

        if ($requestUri === '') {
            $requestUri = '/';
        }

        $routes = $this->routes[$requestMethod] ?? [];

        foreach ($routes as $route => $action) {

            $pattern = preg_replace(
                '/\{[^\/]+\}/',
                '([^/]+)',
                $route
            );

            $pattern = '#^' . $pattern . '$#';

            if (!preg_match($pattern, $requestUri, $matches)) {
                continue;
            }

            array_shift($matches);

            if (is_callable($action)) {
                call_user_func_array($action, $matches);
                return;
            }

            [$controller, $method] = $action;

            $controller = new $controller();

            call_user_func_array(
                [$controller, $method],
                $matches
            );

            return;
        }

        http_response_code(404);

        die('404 - Página no encontrada');
    }
}