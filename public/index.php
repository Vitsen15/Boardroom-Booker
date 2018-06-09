<?php

define('ROOT', dirname(__DIR__) . DIRECTORY_SEPARATOR);

define('APP', ROOT . 'application' . DIRECTORY_SEPARATOR);

require ROOT.'autoload.php';

use Core\Application;

Application::getInstance()->start();
