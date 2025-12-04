<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../core/Database.php';
require_once __DIR__ . '/../core/App.php';
require_once __DIR__ . '/../core/Controller.php';
require_once __DIR__ . '/../core/Model.php';

// simple PSR-4-like autoloader for controllers/models in app/
spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . "/../app/controllers/{$class}.php",
        __DIR__ . "/../app/models/{$class}.php",
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

$app = new App();
