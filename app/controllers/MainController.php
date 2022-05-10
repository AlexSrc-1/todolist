<?php

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\models\User;

class MainController extends Controller
{
	public function indexAction()
    {
		$this->view->render('Todo list', []);
	}

    public function loginAction()
    {
        if (isset($_POST["login"]) && isset($_POST["password"])) {
            if (!empty($_POST["login"]) && !empty($_POST["password"])) {
                $user = User::select('username, password_hash, token')
                    ->findOne(['username' => $_POST["login"]]);
                if (isset($user) && password_verify($_POST["password"], $user->password_hash)) {
                    $_SESSION['todolist_token'] = $user->token;
                    App::$router->redirect('/');
                }
                $_POST = [];
                $error = "Invalid username or password!";
            } else {
                $error = "Login and password required!";
            }
        }
        $this->view->render('Login', ['error' => $error]);
    }

    public function logoutAction()
    {
        unset($_SESSION['todolist_token']);
        App::$router->redirect('/');
    }

}