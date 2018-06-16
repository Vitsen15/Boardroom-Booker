<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Boardroom Booker</title>
    <link rel="stylesheet" type="text/css" href="<?= URL ?>/styles/main.css"/>
</head>
<body>
<nav>
    <ul>
        <? if (\Core\Application::getInstance()->checkAuth()): ?>
            <li>
                <a href="<?= URL ?>/calendar">
                    Calendar
                </a>
            </li>
            <li>
                <a href="<?= URL ?>/logout">
                    Logout
                </a>
            </li>
        <? endif; ?>
    </ul>
</nav>
<? include $template; ?>

<script src="<?= URL ?>/js/main.js" type="text/javascript"></script>
</body>
</html>