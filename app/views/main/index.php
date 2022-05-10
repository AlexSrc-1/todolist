<?php

use app\core\Router;

?>
<section class="banner" role="banner">
    <div class="banner-black">
        <div class="container">
            <div class="col-md-10 col-md-offset-1 m-auto">
                <div class="banner-text text-center">
                    <h1>Your favorite task planner</h1>
                    <p>Complete tasks right now!</p>
                    <a href="<?= Router::createUrl('/tasks/list')?>" class="btn btn-danger btn-lg">Go to tasks</a>
                </div>
            </div>
        </div>
    </div>
</section>