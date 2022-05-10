<?php

use app\core\Router;
use app\models\Task;
use app\models\User;
?>
<div>
    <a role="button" href="<?= Router::createUrl('/tasks/create')?>" class="btn btn-success float-right">Add task</a>
    <h3>Task list</h3>
    <div class="form-group">
        <label for="listSort">Sort by</label>
        <select required name="status" class="form-control" id="listSort">
            <?php foreach (Task::$sortFields as $key => $field): ?>
                <option <?= $key == $sort ? 'selected' : '' ?> value="<?= $key ?>">
                    <?= $field ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <?php foreach ($tasks as $task): ?>
        <div class="task">
            <div class="task-header">
                <h6><span class="<?= Task::$statusesClass[$task->status] ?>"><?= Task::$statuses[$task->status] ?></span></h6>
                <div class="task-datetime text-secondary"><?= $task->created_at ?></div>
            </div>
            <?php if ($task->updated_by): ?>
                <small class="float-right">Updated by admin</small>
            <?php endif; ?>
            <div class="task-username">Username: <?= $task->username ?></div>
            <div class="task-email">Email: <?= $task->email ?></div>
            <div class="task-text">Task text: <?= $task->text ?></div>
            <?php if (!User::isGuest() && User::identify()->isAdmin()): ?>
                <a role="button" class="btn btn-primary float-right" href="<?= Router::createUrl('/tasks/edit?id=' . $task->id )?>">Edit</a>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    <?php echo Task::createLinks('pagination' ); ?>
</div>
