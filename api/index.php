<?php

// ✅ Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// ✅ Debug - check if file exists
if (!file_exists(__DIR__ . '/../public/index.php')) {
    die('public/index.php not found');
}

require __DIR__ . '/../public/index.php';