<?php

namespace app\controllers;

use app\core\App;
use app\core\Controller;
use app\models\Task;
use app\models\User;

class TasksController extends Controller
{
	public function listAction()
    {
        $limit = ( isset( $_GET['limit'] ) ) ? $_GET['limit'] : 3;
        $page = ( isset( $_GET['page'] ) ) ? $_GET['page'] : 1;
        $sort = ( isset( $_GET['sort'] ) ) ? $_GET['sort'] : '-created_at';
        $tasks = Task::select('id, username, text, email, status, created_at, updated_by')
            ->limit($limit)
            ->page($page)
            ->sort($sort)
            ->findAll([]);
        $this->view->render('Task list', ['tasks' => $tasks, 'sort' => $sort]);
    }

    public function editAction($id)
    {
        if (User::isGuest() || !User::identify()->isAdmin()) {
            App::$router->redirect('/login');
        }
        if (empty($id)) {
            throw new \Exception('Missing required parameters');
        }

        $task = Task::select('id, username, text, email, status')
            ->findOne(['id' => $id]);
        if (!isset($task)) {
            throw new \Exception('Task not found');
        }
        if (!empty($_POST["email"]) &&
            !empty($_POST["username"]) &&
            !empty($_POST["text"]) &&
            isset($_POST["status"])
        ) {
            $task->email = $_POST["email"];
            $task->username = $_POST["username"];
            $task->text = $_POST["text"];
            $task->status = intval($_POST["status"]);
            if (!$task->save()) {
                throw new \Exception('Failed to save');
            }
            App::$router->redirect('/tasks/list');
            $_POST = [];
        }
        $this->view->render('Edit task', ['task' => $task]);
    }

    public function createAction()
    {
        if (!empty($_POST["email"]) && !empty($_POST["username"]) && !empty($_POST["text"])) {
            $task = new Task([
                'email' => $_POST["email"],
                'username' => $_POST["username"],
                'text' => $_POST["text"]
            ]);
            if (!$task->save()) {
                throw new \Exception('Failed to save');
            }
            App::$router->redirect('/tasks/list');
            $_POST = [];
        }
        $this->view->render('Create task', ['task' => $task]);
    }
}