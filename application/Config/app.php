<?php

/**
 * Configuration for: URL and paths
 */
define('PUBLIC_FOLDER_PATH', ROOT . 'public' . DIRECTORY_SEPARATOR);
define('URL', $_SERVER['HTTP_HOST']);
define('VIEWS_PATH', APP . 'View' . DIRECTORY_SEPARATOR);
define('LAYOUT_PATH', VIEWS_PATH . 'layouts/app.php');

require_once 'db.php';

