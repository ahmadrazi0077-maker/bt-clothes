#!/bin/bash

# Install composer dependencies
composer install --no-dev --prefer-dist --optimize-autoloader

# Copy public folder to root (for Vercel)
cp -r public/* ./

# Create storage symlink
php artisan storage:link --force

# Optimize
php artisan optimize