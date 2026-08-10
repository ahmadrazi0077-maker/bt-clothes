<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');

try {
    echo "A<br>";

    require __DIR__ . '/../vendor/autoload.php';
    echo "B<br>";

    $app = require __DIR__ . '/../bootstrap/app.php';
    echo "C<br>";

    $request = Illuminate\Http\Request::capture();
    echo "D<br>";

    $response = $app->handleRequest($request);
    echo "E<br>";

    $response->send();
    echo "F<br>";

} catch (\Throwable $e) {

    http_response_code(500);

    echo "<h1>Laravel Error</h1>";
    echo "<strong>Type:</strong> " . get_class($e) . "<br>";
    echo "<strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
    echo "<strong>File:</strong> " . htmlspecialchars($e->getFile()) . "<br>";
    echo "<strong>Line:</strong> " . $e->getLine() . "<br>";

    echo "<pre>";
    echo htmlspecialchars($e->getTraceAsString());
    echo "</pre>";
}