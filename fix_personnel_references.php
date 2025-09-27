<?php

// Script to fix all references from 'personnel' table to 'personnels' table

$file = 'routes/web.php';
$content = file_get_contents($file);

// Replace all instances of DB::table('personnel') with DB::table('personnels')
$content = str_replace("DB::table('personnel')", "DB::table('personnels')", $content);
$content = str_replace("\\DB::table('personnel')", "\\DB::table('personnels')", $content);

// Replace all instances of Schema::hasTable('personnel') with Schema::hasTable('personnels')
$content = str_replace("Schema::hasTable('personnel')", "Schema::hasTable('personnels')", $content);
$content = str_replace("\\Schema::hasTable('personnel')", "\\Schema::hasTable('personnels')", $content);

// Write the corrected content back
file_put_contents($file, $content);

echo "Fixed all personnel table references in $file\n";
echo "Changed 'personnel' to 'personnels' in all DB and Schema references\n";