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

// Perbaikan URI untuk Vercel
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';

if (! str_starts_with($requestUri, '/api/')) {
    $_SERVER['REQUEST_URI'] = '/api'.(
        str_starts_with($requestUri, '/') ? $requestUri : '/'.$requestUri
    );
}

$app->handleRequest(Request::capture());