<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::any('/__debug', function (\Illuminate\Http\Request $r) {
    return [
        'path' => $r->path(),
        'uri' => $r->getRequestUri(),
        'server_uri' => $_SERVER['REQUEST_URI'] ?? null,
        'script' => $_SERVER['SCRIPT_NAME'] ?? null,
    ];
});