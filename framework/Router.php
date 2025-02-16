<?php

class Router
{
    private $routes = [];
    private $notFound;

    public function get($route, $callback)
    {
        $this->routes['GET'][] = ['pattern' => $this->convertPattern($route), 'callback' => $callback];
    }

    public function post($route, $callback)
    {
        $this->routes['POST'][] = ['pattern' => $this->convertPattern($route), 'callback' => $callback];
    }

    public function setNotFound($callback)
    {
        $this->notFound = $callback;
    }

    public function run()
    {
        header('Content-Type: application/json');

        $method = $_SERVER['REQUEST_METHOD'];
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (!isset($this->routes[$method])) {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
            return;
        }

        foreach ($this->routes[$method] as $route) {
            if (preg_match($route['pattern'], $uri, $matches)) {
                array_shift($matches);
                call_user_func($route['callback'], $matches);
                return;
            }
        }

        if ($this->notFound) {
            call_user_func($this->notFound);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    }

    private function convertPattern($route)
    {
        $route = preg_replace('/@(\w+)/', '([^/]+)', $route);

        $route = str_replace('*', '.*', $route);

        return '#^' . $route . '$#';
    }
}