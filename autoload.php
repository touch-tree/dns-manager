<?php

spl_autoload_register(static function ($class): void {
    $class_path = realpath(__DIR__ . DIRECTORY_SEPARATOR . str_replace('\\', '/', $class) . '.php');

    if ($class_path && file_exists($class_path)) {
        require_once $class_path;
    }
});