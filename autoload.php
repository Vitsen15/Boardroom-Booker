<?php

spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class);
    $filePath = APP . $path . '.php';

    if (!file_exists($filePath)) {
        throw new Exception("Required class not found in path: \"{$path}\"");
    }

    require_once $filePath;
});
