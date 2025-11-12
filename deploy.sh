#!/bin/bash

# Digital Marketplace Deployment Script
# This script handles cache optimization for production deployment

echo "Starting deployment process..."

# Clear all caches before rebuilding
echo "Clearing caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Rebuild optimized caches
echo "Building optimized caches..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
echo "Optimizing autoloader..."
composer dump-autoload --optimize

echo "Deployment caching complete!"
echo ""
echo "Note: Remember to run 'php artisan migrate --force' if there are pending migrations"
echo "Note: Remember to run 'npm run build' to compile frontend assets"
echo "Note: Remember to restart queue workers after deployment"
