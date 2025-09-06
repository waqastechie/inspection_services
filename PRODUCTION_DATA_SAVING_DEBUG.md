# 🚨 PRODUCTION DATA SAVING ISSUE - DEBUG GUIDE

## Problem
Not all inspection data is saving to the database in production, even though the same code works locally.

## Most Likely Causes

### 1. **Missing Database Columns** (MOST COMMON)
Your production database may be missing columns that were added in recent migrations.

**Quick Check:**
```bash
php production_database_diagnostic.php
```

### 2. **Migration Status Mismatch**
Some migrations may not have run in production.

**Check Migration Status:**
```bash
php artisan migrate:status
```

**Run Missing Migrations:**
```bash
php artisan migrate --force
```

### 3. **Database Column Constraints**
Some fields may have different constraints (NOT NULL, length limits) in production.

## 🔧 IMMEDIATE FIX STEPS

### Step 1: Run Diagnostic Script
```bash
cd /path/to/your/production/site
php production_database_diagnostic.php
```

This will show you:
- ✅ Database connection status
- 📋 All tables in database
- 🔍 Missing columns in inspections table
- 📊 Migration status
- 🧪 Test inspection creation

### Step 2: Fix Missing Columns
If the diagnostic shows missing columns, run:
```bash
php artisan migrate --force
```

### Step 3: Manual Column Addition (if migrations fail)
If migrations fail, manually add missing columns:

```sql
-- Add missing form fields
ALTER TABLE inspections ADD COLUMN area_of_examination VARCHAR(255) NULL AFTER location;
ALTER TABLE inspections ADD COLUMN services_performed TEXT NULL AFTER area_of_examination;
ALTER TABLE inspections ADD COLUMN contract VARCHAR(255) NULL AFTER services_performed;
ALTER TABLE inspections ADD COLUMN work_order VARCHAR(255) NULL AFTER contract;
ALTER TABLE inspections ADD COLUMN purchase_order VARCHAR(255) NULL AFTER work_order;
ALTER TABLE inspections ADD COLUMN client_job_reference VARCHAR(255) NULL AFTER purchase_order;
ALTER TABLE inspections ADD COLUMN job_ref VARCHAR(255) NULL AFTER client_job_reference;
ALTER TABLE inspections ADD COLUMN standards VARCHAR(255) NULL AFTER job_ref;
ALTER TABLE inspections ADD COLUMN local_procedure_number VARCHAR(255) NULL AFTER standards;
ALTER TABLE inspections ADD COLUMN drawing_number VARCHAR(255) NULL AFTER local_procedure_number;
ALTER TABLE inspections ADD COLUMN test_restrictions TEXT NULL AFTER drawing_number;
ALTER TABLE inspections ADD COLUMN surface_condition TEXT NULL AFTER test_restrictions;
ALTER TABLE inspections ADD COLUMN inspector_comments TEXT NULL AFTER general_notes;
ALTER TABLE inspections ADD COLUMN next_inspection_date DATE NULL AFTER next_inspection_due;

-- Add service inspector columns
ALTER TABLE inspections ADD COLUMN lifting_examination_inspector BIGINT UNSIGNED NULL AFTER inspector_signature;
ALTER TABLE inspections ADD COLUMN load_test_inspector BIGINT UNSIGNED NULL AFTER lifting_examination_inspector;
ALTER TABLE inspections ADD COLUMN thorough_examination_inspector BIGINT UNSIGNED NULL AFTER load_test_inspector;
ALTER TABLE inspections ADD COLUMN mpi_service_inspector BIGINT UNSIGNED NULL AFTER thorough_examination_inspector;
ALTER TABLE inspections ADD COLUMN visual_inspector BIGINT UNSIGNED NULL AFTER mpi_service_inspector;

-- Add foreign key constraints (only if personnels table exists)
ALTER TABLE inspections ADD FOREIGN KEY (lifting_examination_inspector) REFERENCES personnels(id) ON DELETE SET NULL;
ALTER TABLE inspections ADD FOREIGN KEY (load_test_inspector) REFERENCES personnels(id) ON DELETE SET NULL;
ALTER TABLE inspections ADD FOREIGN KEY (thorough_examination_inspector) REFERENCES personnels(id) ON DELETE SET NULL;
ALTER TABLE inspections ADD FOREIGN KEY (mpi_service_inspector) REFERENCES personnels(id) ON DELETE SET NULL;
ALTER TABLE inspections ADD FOREIGN KEY (visual_inspector) REFERENCES personnels(id) ON DELETE SET NULL;
```

## 🔍 DEBUGGING SPECIFIC ISSUES

### Check Database Logs
```bash
# Check MySQL error log
tail -f /var/log/mysql/error.log

# Check Laravel logs
tail -f storage/logs/laravel.log
```

### Test Form Submission
1. Fill out inspection form in production
2. Submit form
3. Check Laravel logs for errors
4. Check if partial data was saved:
   ```sql
   SELECT * FROM inspections ORDER BY created_at DESC LIMIT 1;
   ```

### Check PHP Configuration
- **Max Input Vars**: `max_input_vars` should be at least 1000
- **Post Max Size**: `post_max_size` should be at least 32M
- **Upload Max**: `upload_max_filesize` should be at least 32M

```bash
php -i | grep -E "(max_input_vars|post_max_size|upload_max_filesize)"
```

## 🚀 VERIFICATION STEPS

### 1. Test Basic Inspection Creation
```php
$inspection = App\Models\Inspection::create([
    'inspection_number' => 'TEST123',
    'client_name' => 'Test Client',
    'project_name' => 'Test Project',
    'location' => 'Test Location',
    'inspection_date' => '2025-09-06',
    'lead_inspector_name' => 'Test Inspector',
    'lead_inspector_certification' => 'Test Cert',
    'equipment_type' => 'Test Equipment',
    'status' => 'draft'
]);
```

### 2. Test Additional Fields
```php
$inspection->update([
    'area_of_examination' => 'Test Area',
    'services_performed' => 'Test Services',
    'inspector_comments' => 'Test Comments',
    'lifting_examination_inspector' => 1 // ID of existing personnel
]);
```

### 3. Test Complete Form Submission
Create a test inspection through your actual form interface.

## 📊 COMMON PRODUCTION vs DEVELOPMENT DIFFERENCES

| Issue | Development | Production | Solution |
|-------|-------------|------------|-----------|
| Missing Columns | ✅ All columns | ❌ Missing new columns | Run migrations |
| Personnel Table | `personnels` | `personnel` | Fix table name |
| PHP Limits | High limits | Low limits | Increase PHP limits |
| Database Size | Small | Large | Check constraints |
| Error Reporting | Visible | Hidden | Check logs |

## 🆘 EMERGENCY CONTACT INFORMATION

If you need immediate assistance:
1. Check the diagnostic output first
2. Run the suggested fixes
3. Test with a simple form submission
4. Check Laravel logs for specific errors

The most common issue is missing database columns in production. Run the diagnostic script first!
