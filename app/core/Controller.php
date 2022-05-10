<?php

namespace app\core;

abstract class Controller {

	public $route;
	public $view;
    public $model;

	public function __construct($route)
    {
		$this->route = $route;
		$this->view = new View($route);
		$this->model = $this->loadModel($route['controller']);
	}

    /**
     * Loading a controller model by its name
     *
     * @param $name - controller name
     * @return mixed|null
     */
	public function loadModel($name)
    {
		$path = 'app\models\\'.ucfirst($name);
		if (class_exists($path)) {
			return new $path;
		}
        return null;
	}

    /**
     * Event before action
     *
     * @return bool
     */
    public function beforeAction(): bool
    {
        return true;
    }

    /**
     * Event after action
     *
     * @return bool
     */
    public function afterAction(): bool
    {
        return false;
    }

}