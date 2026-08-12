<?php

// ✅ Simple test first
echo "<!-- public/index.php loaded -->";

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ✅ Check vendor
if (!file_exists(__DIR__.'/../vendor/autoload.php')) {
    die('vendor/autoload.php not found');
}

require __DIR__.'/../vendor/autoload.php';

// ✅ Check bootstrap
if (!file_exists(__DIR__.'/../bootstrap/app.php')) {
    die('bootstrap/app.php not found');
}

$app = require_once __DIR__.'/../bootstrap/app.php';
$app->handleRequest(Request::capture());