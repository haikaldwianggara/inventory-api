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

// Debug request dari Vercel
$request = Request::capture();

header('Content-Type: application/json');

echo json_encode([
    'request_uri' => $_SERVER['REQUEST_URI'] ?? null,
    'path_info' => $_SERVER['PATH_INFO'] ?? null,
    'script_name' => $_SERVER['SCRIPT_NAME'] ?? null,
    'laravel_path' => $request->path(),
    'laravel_url' => $request->url(),
    'method' => $request->method(),
], JSON_PRETTY_PRINT);

exit;