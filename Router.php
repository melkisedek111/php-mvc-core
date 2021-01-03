<?php

namespace thecore\phpmvc;

use thecore\phpmvc\exception\NotFoundException;
/**
 *
 * @package thecore\phpmvc
 */
class Router
{
    public Request $request;
    public Response $response;
    protected array $routes = [];

    /**
     * @param \thecore\phpmvc\Request $request
     * @param \thecore\phpmvc\Response $response
     */
    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    public function get($path, $callback)
    {
        $this->routes['get'][$path] = $callback;
    }
    public function post($path, $callback)
    {
        $this->routes['post'][$path] = $callback;
    }
    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            throw new NotFoundException();
        }

        if (is_string($callback)) {
            return Application::$app->view->renderView($callback);
        }

        if (is_array($callback)) {
            /** @var \thecore\phpmvc\Controller $controller */
            $controller = new $callback[0](); //  instance of a controller take the 0 index which is the controller name "SiteController::class" and create new instance and mutate the callback[0]
            Application::$app->controller = $controller;
            $controller->action = $callback[1];
            $callback[0] = $controller;
            foreach ($controller->getMiddlewares() as $middleware) {
                $middleware->execute();
            }
        }
        return call_user_func($callback, $this->request, $this->response); // accept all the arguments of the callback
    }
}