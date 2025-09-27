@echo off
echo Cleaning up test and development files...

cd /d "C:\xampp\htdocs\inspection_services"

:: Remove test files
if exist "test_*.php" del /q "test_*.php"
if exist "check_*.php" del /q "check_*.php"  
if exist "debug_*.php" del /q "debug_*.php"
if exist "create_test*.php" del /q "create_test*.php"
if exist "create_sample*.php" del /q "create_sample*.php"
if exist "create_personnel.php" del /q "create_personnel.php"
if exist "create_admin_user.php" del /q "create_admin_user.php"
if exist "seed_personnel.php" del /q "seed_personnel.php"
if exist "fix_validation.php" del /q "fix_validation.php"
if exist "enhance_inspection_images.php" del /q "enhance_inspection_images.php"
if exist "emergency_syntax_fix.php" del /q "emergency_syntax_fix.php"
if exist "syntax_error_debug.php" del /q "syntax_error_debug.php"
if exist "temp_test_controller.php" del /q "temp_test_controller.php"
if exist "analyze_service_data.php" del /q "analyze_service_data.php"
if exist "production_test.php" del /q "production_test.php"
if exist "production_database_diagnostic.php" del /q "production_database_diagnostic.php"
if exist "update_inspection_images.php" del /q "update_inspection_images.php"
if exist "detailed_check_34.php" del /q "detailed_check_34.php"
if exist "simple_service_test.php" del /q "simple_service_test.php"

:: Remove text and HTML files
if exist "auth_cookies.txt" del /q "auth_cookies.txt"
if exist "cookies.txt" del /q "cookies.txt"
if exist "test_cookies.txt" del /q "test_cookies.txt"
if exist "debug_console.html" del /q "debug_console.html"
if exist "test_*.html" del /q "test_*.html"
if exist "test_*.pdf" del /q "test_*.pdf"

:: Clean up public directory
cd /d "C:\xampp\htdocs\inspection_services\public"
if exist "test_*.php" del /q "test_*.php"
if exist "test-*.html" del /q "test-*.html"
if exist "debug-*.php" del /q "debug-*.php"
if exist "fix_*.php" del /q "fix_*.php"
if exist "csrf_*.html" del /q "csrf_*.html"
if exist "responsive-*.html" del /q "responsive-*.html"
if exist "simple-*.html" del /q "simple-*.html"

echo Cleanup completed!
pause
