# Settings Table Setup Guide

## Overview
The `settings` table stores application configuration values in a key-value format. This guide covers initialization and verification of the settings system for the featured movie functionality.

## Table Schema

```sql
CREATE TABLE `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

**Note**: This table is already defined in `schema.sql` (lines 172-180).

## Setup Steps

### Step 1: Import Main Schema
```bash
mysql -u root -p cgv_booking < database/schema.sql
```

### Step 2: Run Initialization Script
```bash
mysql -u root -p cgv_booking < database/init-settings.sql
```

This script will:
- Create the settings table (if not exists)
- Insert a sample movie (if no movies exist)
- Set the `featured_movie_id` setting to the first movie's ID
- Run verification queries

## Verification

### Method 1: Via Test Script (PHP)
```bash
php test-settings.php
```

Expected output:
```
====================================
SETTINGS TABLE VERIFICATION TEST
====================================

Test 1: Settings table structure
[✓] Settings table EXISTS
   Columns:
   - id (int(11))
   - setting_key (varchar(100))
   - setting_value (text)
   - created_at (timestamp)
   - updated_at (timestamp)

...
```

### Method 2: Direct SQL Queries
```sql
-- Check if featured_movie_id is set
SELECT * FROM settings WHERE setting_key = 'featured_movie_id';

-- Get the featured movie
SELECT m.* FROM movies m
WHERE m.id = (
    SELECT setting_value FROM settings WHERE setting_key = 'featured_movie_id'
);

-- Get featured movie with fallback to first movie
SELECT m.* FROM movies m
WHERE m.id = (
    SELECT COALESCE(
        (SELECT setting_value FROM settings WHERE setting_key='featured_movie_id'),
        (SELECT MIN(id) FROM movies)
    )
)
LIMIT 1;
```

## Using Settings in Code

### Via Settings Model
```php
<?php
require_once 'configs/database.php';
require_once 'core/Database.php';
require_once 'app/Models/Model.php';
require_once 'app/Models/Settings.php';

$settings = new Settings();

// Get featured movie ID
$featured_id = $settings->getFeaturedMovieId();
// Returns: int (movie ID) or null (if no movies exist)

// Get any setting by key
$value = $settings->getByKey('featured_movie_id');
// Returns: value or null if not found

// Set a value (creates or updates)
$settings->set('featured_movie_id', 5);
// Returns: true on success
```

### Direct Query (for performance)
```php
<?php
$db = Database::getInstance()->getPdo();

// Get featured movie ID with fallback
$stmt = $db->prepare("
    SELECT COALESCE(
        (SELECT setting_value FROM settings WHERE setting_key = ?),
        (SELECT MIN(id) FROM movies)
    )
");
$stmt->execute(['featured_movie_id']);
$featured_id = $stmt->fetchColumn();
```

## Settings Keys

Currently defined:
- `featured_movie_id` (INT) - Movie ID to display as featured on homepage

Future keys can be added by:
```sql
INSERT INTO settings (setting_key, setting_value) VALUES ('new_key', 'value');
```

## Troubleshooting

### Issue: "Settings table not found"
**Solution**: Run `database/init-settings.sql` to create it
```bash
mysql -u root -p cgv_booking < database/init-settings.sql
```

### Issue: "No featured movie set"
**Solution**: Run the initialization script or manually:
```sql
INSERT INTO settings (setting_key, setting_value) 
VALUES ('featured_movie_id', (SELECT MIN(id) FROM movies))
ON DUPLICATE KEY UPDATE setting_value = (SELECT MIN(id) FROM movies);
```

### Issue: PDOException when using Settings model
**Possible causes**:
1. Database connection not configured (check `configs/database.php`)
2. Settings table doesn't exist (run init script)
3. Database is not running

**Solution**: Verify connection with:
```php
try {
    $settings = new Settings();
    $id = $settings->getFeaturedMovieId();
    echo "Success: " . $id;
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
```

## Files Involved

- **Table definition**: `database/schema.sql` (lines 172-180)
- **Initialization**: `database/init-settings.sql`
- **Model**: `app/Models/Settings.php`
- **Test script**: `test-settings.php`
- **Database config**: `configs/database.php`

## Next Steps

Once settings table is verified:
1. Use `Settings::getFeaturedMovieId()` in controller
2. Query `movies` table using that ID
3. Pass movie data to view for rendering
