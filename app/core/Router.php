<?php

namespace app\core;

use app\models\User;

class Router {

    protected $routes = [];
    protected $arguments = [];
    protected $params = ['controller' => 'main'];
    
    public function __construct()
    {
        $controllers = array_diff(scandir('app/controllers'), array('..', '.'));
        foreach ($controllers as $controller) {
            $controller = str_replace(['.php', 'Controller'], '', $controller);

            $path = 'app\controllers\\'.$controller.'Controller';

            $methods = get_class_methods($path);
            $methods = array_diff($methods, array('beforeAction', 'afterAction'));

            foreach ($methods as $method) {
                if (strripos($method, 'Action')) {

                    $method = $this->convertCase(str_replace('Action', '', $method));
                    $controller = strtolower($controller);

                    $this->add($controller . '/' . $method, [
                        'controller' => $controller,
		                'action' => $method,

                    ]);
                }
            }
        }
        $arr = require 'app/config/routes.php';
        foreach ($arr as $key => $val) {
            $this->add($key, $val);
        }
    }

    /**
     * Adding links to routing
     *
     * @param $route - link
     * @param $params - controller and action
     * @return void
     */
    private function add($route, $params)
    {
        $route = '#^'.$route.'$#';
        $this->routes[$route] = $params;
    }

    private function match(): bool
    {
        $url = $this->getRouteUrl();
        $url = trim($url, '/');
        $link = explode('?', $url)[0];
        $arguments = explode('&', explode('?', $url)[1]);
        foreach ($arguments as $argument) {
            $argument =  explode('=', $argument);
            $this->arguments[$argument[0]] = $argument[1];
        }
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $link, $matches)) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    /**
     * Run router
     *
     * @return void
     */
    public function run()
    {
        $url = $this->getRouteUrl();
        $url = explode('?', $url)[0];
        if (!User::isGuest() && $url == '/login') {
            $this->redirect('/');
        }

        if ($this->match()) {
            $path = 'app\controllers\\'.ucfirst($this->params['controller']).'Controller';
            if (class_exists($path)) {
                $action = $this->params['action'].'Action';
                if (method_exists($path, $action)) {
                    $controller = new $path($this->params);
                    if ($controller->beforeAction()) {
                        call_user_func_array(
                            [$controller, $action],
                            $this->arguments
                        );
                    }
                    $controller->afterAction();
                } else {
                    View::errorCode(404);
                }
            } else {
                View::errorCode(404);
            }
        } else {
            View::errorCode(404);
        }
    }

    /**
     * Redirect user
     *
     * @param $url - url page
     * @return void
     */
    public function redirect($url)
    {
        header( 'Location: ' . Router::createUrl($url), true);
        die();
    }

    /**
     * Convert camelCase to kebab-case
     *
     * @param $input
     * @return string
     */
    private function convertCase ($input): string
    {
        $pattern = '!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!';
        preg_match_all($pattern, $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ?
                strtolower($match) :
                lcfirst($match);
        }
        return implode('-', $ret);
    }

    private function getRouteUrl()
    {
        $dir = $_SERVER['SCRIPT_NAME'];
        return str_replace($dir, '', $_SERVER['REQUEST_URI']);
    }

    public static function createUrl($url)
    {
        return $_SERVER['SCRIPT_NAME'] . $url;
    }
}