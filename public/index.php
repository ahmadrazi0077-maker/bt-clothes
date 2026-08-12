<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ✅ Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->handleRequest(Request::capture());