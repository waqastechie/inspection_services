# 🔧 Production Login Troubleshooting Guide

## Issue: "The provided credentials do not match our records"

### Quick Fix Options:

### Option 1: Use Laravel Command (Recommended)

```bash
# In your production server, run:
php artisan admin:create

# This will create/update the admin user with:
# Email: admin@inspectionservices.com
# Password: admin123
```

### Option 2: Run the PHP Script

```bash
# In your project root directory:
php create_admin_user.php
```

### Option 3: Use Production Setup Script

```bash
# For Windows:
production-setup.bat

# For Linux/Mac:
chmod +x production-setup.sh
./production-setup.sh
```

### Option 4: Manual Database Check

```sql
-- Check if admin user exists
SELECT * FROM users WHERE email = 'admin@inspectionservices.com';

-- If user exists, update password:
UPDATE users SET password = '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' WHERE email = 'admin@inspectionservices.com';

-- If user doesn't exist, run the seeder:
php artisan db:seed --class=SuperAdminSeeder
```

## Common Production Issues:

### 1. Database Not Migrated

```bash
php artisan migrate --force
```

### 2. Database Not Seeded

```bash
php artisan db:seed --force
```

### 3. Cache Issues

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 4. Environment Issues

-   Check `.env` file has correct database settings
-   Verify `APP_ENV=production`
-   Ensure `APP_KEY` is set

### 5. Permissions Issues

```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## Default Login Credentials:

After running any of the above solutions:

**Email:** `admin@inspectionservices.com`
**Password:** `admin123`
**Role:** `super_admin`

## Alternative Admin Emails to Try:

If the above doesn't work, check if users exist with these emails:

-   `admin@company.com` (password: `password`)
-   `inspector@company.com` (password: `password`)

## Need Custom Credentials?

Run with custom options:

```bash
php artisan admin:create --email=your@email.com --password=yourpassword --name="Your Name"
```

## Still Not Working?

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server error logs
3. Verify database connection: `php artisan tinker` then `DB::connection()->getPdo()`
4. Test with a simple user creation script

## Contact Support:

If none of these solutions work, there might be a deeper configuration issue with your production environment.
