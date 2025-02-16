<?php

spl_autoload_register(function ($class) {
    $file = __DIR__ . '/' . $class . '.php';
    if (file_exists($file) && $file != 'Include.php') {
        require_once $file;
    }
});
