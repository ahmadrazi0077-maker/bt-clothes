<?php

// ✅ Serve static files
$path = $_SERVER['REQUEST_URI'];
if (strpos($path, '/css/') === 0) {
    $file = __DIR__ . '/../public' . $path;
    if (file_exists($file)) {
        header('Content-Type: text/css');
        readfile($file);
        exit;
    }
}

require __DIR__ . '/../public/index.php';