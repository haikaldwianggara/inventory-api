<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require __DIR__.'/../bootstrap/app.php';

$storage = sys_get_temp_dir().'/inventory-laravel';

foreach (['framework/cache/data', 'framework/sessions', 'framework/views'] as $dir) {
    $path = $storage.'/'.$dir;

    if (! is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

$app->useStoragePath($storage);

// Mengambil path yang diteruskan oleh Vercel
$route = $_GET['route'] ?? '/';

// Mengatur ulang URI agar Laravel dapat membaca route
$_SERVER['REQUEST_URI'] = $route;
$_SERVER['PATH_INFO'] = $route;

$app->handleRequest(Request::capture());