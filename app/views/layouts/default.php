<?php

use app\core\Router;
use app\models\User;
?>
<!--[if IE 8]> <html lang="en" class="ie8"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!--> <html lang="en"> <!--<![endif]-->
<head>
	<title><?= $title; ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= Router::createUrl('/../public/css/site.css')?>"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <script src='<?= Router::createUrl('/../public/js/site.js')?>'></script>
</head>

<body>
    <header id="header" class="header">
        <div class="container">
            <h1 class="logo">
                <a class="scrollto" href="<?= Router::createUrl('/')?>">
                    <span class="logo-icon-wrapper"><img class="logo-icon" src="<?= Router::createUrl('/../public/images/logo-icon.svg')?>" alt="icon"></span>
                    <span class="text"><span class="highlight">TODO</span>LIST</span></a>
            </h1>
            <?php if (!User::isGuest()): ?>
                <a class="header-exit" href="<?= Router::createUrl('/logout')?>">Logout</a>
            <?php else:; ?>
                <a class="header-exit" href="<?= Router::createUrl('/login')?>">Login</a>
            <?php endif; ?>
        </div>
    </header>
    <div class="page">
        <div class="container">
            <?php echo $content; ?>
        </div>
    </div>

    <footer class="footer text-center">
        <div class="container">
            <small class="copyright">Designed with <i class="fa fa-heart"></i> by <a href="http://themes.3rdwavemedia.com/" target="_blank">Xiaoying Riley</a> for developers</small>
        </div>
    </footer>
</body>
</html>