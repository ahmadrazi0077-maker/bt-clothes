<?php

// ✅ Check if vendor exists
if (!file_exists(__DIR__ . '/../vendor/autoload.php')) {
    die('vendor/autoload.php not found. Run composer install.');
}

// Forward Vercel requests to public/index.php
require __DIR__ . '/../public/index.php';