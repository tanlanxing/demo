<?php
namespace server\web\core;


use Swoole\Http\Request;
use Swoole\Http\Response;
use ReflectionClass;

class Router
{
    private $instances = [];

    private $request;

    private $response;

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    public function dispatch()
    {
        $path = $this->request->server['path_info'];
        if ($path == '') {
            $class = '\server\web\controllers\IndexController';
            $action = 'indexAction';
        } elseif (preg_match('#^/([a-z]+)/([a-z]+)/([a-z]+)$#', $path, $match)) {
            $class = '\server\web\modules\\' . $match['1'] . '\controllers\\' . ucfirst($match['2']) . 'Controller';
            $action = $match['3'] . 'Action';
        } elseif (preg_match('#^/([a-z]+)/([a-z]+)$#', $path, $match)) {
            $class = '\server\web\modules\\' . $match['1'] . '\controllers\\' . ucfirst($match['2']) . 'Controller';
            if (class_exists($class)) {
                $action = 'indexAction';
            } else {
                $class = '\server\web\controllers\\' . ucfirst($match['1']) . 'Controller';
                $action = $match['2'] . 'Action';
            }
        } elseif (preg_match('#^/([a-z]+)$#', $path, $match)) {
            $class = '\server\web\controllers\\' . ucfirst($match['1']) . 'Controller';
            $action = 'indexAction';
        } else {
            return $this->e404();
        }

        if (!class_exists($class)) {
            return $this->e404();
        }

        $reflection = new ReflectionClass($class);
        if (!$reflection->hasMethod($action)) {
            return $this->e404();
        }

        $reflectionFunction = $reflection->getMethod($action);
        /** @var Controller $controller */
        $controller = $reflection->newInstanceArgs([$this->request, $this->response]);
        unset($reflection);
        $this->instances[] = $controller;
        $controller->setActionId($action);

        $content = $reflectionFunction->invoke($controller);
        unset($reflectionFunction);

        return $this->response->end($content);
    }

    public function redirect(string $uri)
    {
        $this->response->header('location', $uri);
        return $this->response->end();
    }

    public function e404()
    {
        $this->response->status(404);
        return $this->response->end('<h1>404</h1>');
    }

    public function __destruct()
    {
        foreach ($this->instances as $instance) {
            unset($instance);
        }
    }
}