<?php

use app\core\Router;
use app\models\Task;

?>
<div>
    <form action="<?= Router::createUrl('/tasks/create')?>" method="post" id="login">
        <div class="form-group">
            <label for="taskStatus">Status</label>
            <select required name="status" class="form-control" id="taskStatus">
                <?php foreach (Task::$statuses as $key => $status): ?>
                    <option value="<?= $key ?>">
                        <?= $status ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="taskEmail">Email</label>
            <input
                required
                name="email"
                type="email"
                class="form-control"
                id="taskEmail"
                aria-describedby="emailHelp"
                placeholder="Enter email"
            >
        </div>
        <div class="form-group">
            <label for="taskUsername">User name</label>
            <input
                required
                name="username"
                type="text"
                class="form-control"
                id="taskUsername"
                placeholder="User name"
            >
        </div>
        <div class="form-group">
            <label for="taskText">Task text</label>
            <textarea required name="text" class="form-control" id="taskText" rows="10"></textarea>
        </div>
        <b><button type="submit" class="btn btn-success" id="loginEnter">Save</button></b>
    </form>
</div>
