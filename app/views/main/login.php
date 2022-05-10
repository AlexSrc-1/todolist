<?php

use app\core\Router;

?>
<h3>Login</h3>
<form action="<?= Router::createUrl('/login')?>" method="post" id="login">
    <div class="form-group">
        <label for="loginUsername">Username</label>
        <input
            required
            name="login"
            type="text"
            class="form-control"
            id="loginUsername"
            placeholder="Enter username"
        >
    </div>
    <div class="form-group">
        <label for="loginPassword">Password</label>
        <input
            required
            name="password"
            type="password"
            class="form-control"
            id="loginPassword"
            placeholder="Enter password"
        >
    </div>
	<b><button type="submit" class="btn btn-success" id="loginEnter">Login</button></b>
</form>

<?php if ($error): ?>
    <span class="text-danger"><?= $error ?></span>
<?php endif; ?>