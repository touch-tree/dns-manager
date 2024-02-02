<?php

// PSR-4 inspired autoloader

spl_autoload_register(function ($class) {
    $class_path = realpath(__DIR__ . '/' . str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php');

    if ($class_path && file_exists($class_path)) {
        require_once $class_path;
    }
});