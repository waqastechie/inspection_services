@echo off
echo 🚀 Setting up Inspection Services for Production
echo ==============================================

REM Run migrations
echo 📊 Running database migrations...
php artisan migrate --force

REM Create admin user
echo 👤 Creating admin user...
php artisan admin:create

REM Clear caches
echo 🧹 Clearing application caches...
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

REM Optimize for production
echo ⚡ Optimizing for production...
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo ✅ Production setup completed!
echo.
echo 📋 Default Login Credentials:
echo Email: admin@inspectionservices.com
echo Password: admin123
echo.
echo 🔗 Access your application and login with these credentials.
pause
