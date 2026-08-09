<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ✅ Check vendor
if (!file_exists(__DIR__.'/../vendor/autoload.php')) {
    die('vendor/autoload.php not found');
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// ✅ Check bootstrap/app.php
if (!file_exists(__DIR__.'/../bootstrap/app.php')) {
    die('bootstrap/app.php not found');
}

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->handleRequest(Request::capture());