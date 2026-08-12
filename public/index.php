<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ✅ NO echo/print statements here!
// ❌ Remove: echo "<!-- public/index.php loaded -->";

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->handleRequest(Request::capture());