<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "STEP 1<br>";

require __DIR__ . '/../vendor/autoload.php';

echo "STEP 2<br>";

$app = require_once __DIR__ . '/../bootstrap/app.php';

echo "STEP 3<br>";

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "STEP 4<br>";

$request = Illuminate\Http\Request::capture();

echo "STEP 5<br>";

$response = $kernel->handle($request);

echo "STEP 6<br>";

$response->send();

echo "STEP 7<br>";

$kernel->terminate($request, $response);