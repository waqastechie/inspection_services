# 🗄️ Database Import Guide for Production

## 📋 What's Ready for You:

✅ **Database Dump**: `inspection_services_production_ready.sql`
✅ **Admin Users Created**: Ready to login
✅ **Sample Data**: Clients, Personnel, Equipment, Consumables
✅ **Complete Inspection**: Sample inspection with images

## 🚀 Production Import Steps:

### Step 1: Upload Files to Production Server

```bash
# Upload these files to your production server:
- inspection_services_production_ready.sql
- All application files
- .env file (configure for production)
```

### Step 2: Configure Production Environment

Create/update `.env` file on production:

```env
APP_NAME="Inspection Services"
APP_ENV=production
APP_KEY=base64:+rV/uF/8Z1vMuUsbFOGL0qHuy2qfCkgwC4++a5uvFMI=
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_production_database_name
DB_USERNAME=your_db_username
DB_PASSWORD=your_db_password

# Add other production configurations...
```

### Step 3: Create Production Database

```sql
-- Connect to MySQL on production server
CREATE DATABASE your_production_database_name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 4: Import Database

```bash
# On your production server:
mysql -u your_username -p your_production_database_name < inspection_services_production_ready.sql
```

### Step 5: Set Permissions

```bash
# Set proper file permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### Step 6: Run Production Setup

```bash
# Clear caches and optimize
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Cache for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Generate application key if needed
php artisan key:generate
```

## 👤 Login Credentials:

After import, you can login with:

### Super Administrator:

-   **Email**: `admin@inspectionservices.com`
-   **Password**: `admin123`
-   **Role**: `super_admin`

### Admin User:

-   **Email**: `admin@company.com`
-   **Password**: `password`
-   **Role**: `admin`

### Inspector User:

-   **Email**: `inspector@company.com`
-   **Password**: `password`
-   **Role**: `inspector`

## 📊 What's Included in the Database:

### Users (3 total):

-   1 Super Administrator
-   1 Admin User
-   1 Inspector

### Sample Data:

-   **Clients**: Multiple test clients
-   **Personnel**: Inspectors and technicians
-   **Equipment**: Various inspection equipment
-   **Consumables**: Testing materials
-   **Inspections**: Complete sample inspection with all sections

### Features Ready:

-   ✅ User authentication and roles
-   ✅ Inspection form with all service types
-   ✅ PDF generation
-   ✅ Image upload functionality
-   ✅ Auto-save capability
-   ✅ Inspector assignments
-   ✅ Admin panels for resource management

## 🔧 Troubleshooting:

### If Login Still Doesn't Work:

```bash
# Reset admin password
php artisan admin:create --email=admin@inspectionservices.com --password=newpassword
```

### If Images Don't Display:

```bash
# Create storage link
php artisan storage:link

# Set permissions
chmod -R 755 public/images
```

### If Database Issues:

```bash
# Check database connection
php artisan tinker
DB::connection()->getPdo()
```

## 🎯 Quick Verification Checklist:

After import, verify these work:

-   [ ] Login with admin credentials
-   [ ] Create new inspection
-   [ ] Upload images
-   [ ] Generate PDF
-   [ ] Access admin panels
-   [ ] User management works

## 📞 Support:

If you encounter any issues:

1. Check Laravel logs: `storage/logs/laravel.log`
2. Check web server error logs
3. Verify all file permissions
4. Ensure .env configuration is correct

Your database is now production-ready! 🎉
