<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Vercel Serverless Configuration
|--------------------------------------------------------------------------
| Vercel filesystem read-only kecuali /tmp.
| Cache, session, dan view diarahkan ke /tmp.
*/

$tmp = sys_get_temp_dir() . '/inventory-laravel';

$serverlessEnv = [
    'APP_CONFIG_CACHE'   => $tmp . '/bootstrap/config.php',
    'APP_EVENTS_CACHE'   => $tmp . '/bootstrap/events.php',
    'APP_PACKAGES_CACHE' => $tmp . '/bootstrap/packages.php',
    'APP_ROUTES_CACHE'   => $tmp . '/bootstrap/routes.php',
    'APP_SERVICES_CACHE' => $tmp . '/bootstrap/services.php',
    'VIEW_COMPILED_PATH' => $tmp . '/framework/views',
    'LOG_CHANNEL'        => 'stderr',
    'SESSION_DRIVER'     => 'array',
    'CACHE_STORE'        => 'array',
];

foreach ($serverlessEnv as $key => $value) {
    if (getenv($key) === false || getenv($key) === '') {
        putenv("$key=$value");
        $_ENV[$key] = $value;
        $_SERVER[$key] = $value;
    }
}

/*
|--------------------------------------------------------------------------
| Create Temporary Directories
|--------------------------------------------------------------------------
*/

foreach ([
    'bootstrap',
    'framework/cache/data',
    'framework/sessions',
    'framework/views',
    'logs',
] as $dir) {
    $path = $tmp . '/' . $dir;

    if (!is_dir($path)) {
        mkdir($path, 0775, true);
    }
}

/*
|--------------------------------------------------------------------------
| Load Laravel
|--------------------------------------------------------------------------
*/

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Set Storage Path
|--------------------------------------------------------------------------
*/

$app->useStoragePath($tmp);

/*
|--------------------------------------------------------------------------
| Fix Server Variables for Vercel
|--------------------------------------------------------------------------
*/

$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['PHP_SELF'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = __DIR__ . '/../public/index.php';

/*
|--------------------------------------------------------------------------
| Handle Request
|--------------------------------------------------------------------------
*/

$app->handleRequest(Request::capture());