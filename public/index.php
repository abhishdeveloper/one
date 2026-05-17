<?php

require_once '../config/config.php';

// Autoload Core Libraries
spl_autoload_register(function ($className) {
    $file = '../core/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Init Core Library
$init = new Router();
