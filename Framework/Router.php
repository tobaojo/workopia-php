<?php

namespace Framework;

use App\Controllers\ErrorController;

class Router
{
    protected $routes = [];

    public function registerRoutes($method, $uri, $action)
    {
        list($controller, $controllerMethod) = explode('@', $action);

        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller,
            'controllerMethod' => $controllerMethod
        ];
    }
    /**
     * add a GET route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function get($uri, $controller)
    {
        $this->registerRoutes('GET', $uri, $controller);
    }

    /**
     * add a POST route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function post($uri, $controller)
    {
        $this->registerRoutes('POST', $uri, $controller);
    }

    /**
     * add a PUT route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function put($uri, $controller)
    {
        $this->registerRoutes('PUT', $uri, $controller);
    }

    /**
     * add a DELETE route
     * @param string $uri
     * @param string $controller
     * @return void
     */
    public function delete($uri, $controller)
    {
        $this->registerRoutes('DELETE', $uri, $controller);
    }


    /**
     * Route request
     * @param string $uri,
     * @param string $method
     * @return void
     */
    public function route($uri)
    {
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        foreach ($this->routes as $route) {
            $uriSegments = explode('/', trim($uri, '/'));
            $routeUriSegments = explode('/', trim($route['uri'], '/'));

            $match = true;

            // check if the number of segments match
            if (count($uriSegments) === count($routeUriSegments) && strtoupper($route['method'] === $requestMethod)) {
                $params = [];
                $match = true;

                for ($i = 0; $i < count($uriSegments); $i++) {
                    // If the URIs do not match and there is no param(value inbetween the curly braces)
                    if ($routeUriSegments[$i] !== $uriSegments[$i] && !preg_match('/\{(.+?)\}/', $routeUriSegments[$i])) {
                        $match = false;
                        break;
                    }
                    if (preg_match('/\{(.+?)\}/', $routeUriSegments[$i], $matches)) {
                        $params[$matches[1]] = $uriSegments[$i];
                    }
                }
                if ($match) {
                    $controller = 'App\\Controllers\\' . $route['controller'];
                    $controllerMethod = $route['controllerMethod'];

                    // Instantiate controller and call method
                    $controllerInstance = new $controller();
                    $controllerInstance->$controllerMethod($params);
                    return;
                }
            }
        }
        ErrorController::notFound();
    }
}
