# Task 1: Settings Table & Featured Movie Implementation

## Executive Summary
Task 1 successfully implements a settings management system for the homepage rewrite project. The `settings` table enables dynamic configuration of the featured movie without code changes.

**Status**: ✅ COMPLETE  
**Files Created**: 5  
**Lines of Code**: 378  
**Ready for**: Task 2 (Controller Implementation)

---

## Deliverables

### 1. Settings Model (`app/Models/Settings.php`)
A PHP model class that extends the base `Model.php` with specialized settings functionality.

**Methods**:
- `getByKey(string $key, $default = null)` - Retrieve any setting
- `set(string $key, $value): bool` - Create/update a setting
- `getFeaturedMovieId(): int|null` - Get featured movie with fallback logic

**Connection**: PDO via `Database::getInstance()->getPdo()`

### 2. Database Initialization (`database/init-settings.sql`)
SQL script that prepares the database for production use.

**Operations**:
- Creates settings table if not exists
- Inserts sample movie if database is empty
- Sets featured_movie_id to first movie
- Runs 3 verification queries
- Documents fallback SQL patterns

### 3. Setup Documentation (`database/SETTINGS_SETUP.md`)
Complete guide for setup, usage, and troubleshooting.

**Sections**:
- Table schema reference
- Step-by-step setup procedure
- Verification methods (SQL + PHP)
- Code usage examples
- Troubleshooting guide

### 4. Test Script (`test-settings.php`)
Comprehensive PHP verification script.

**Tests**:
1. Table structure verification
2. Settings retrieval
3. Featured movie with fallback
4. Settings update
5. SQL fallback query

### 5. Documentation Updates
Updated notepad files with implementation context.

---

## Technical Architecture

### Database Schema
```sql
CREATE TABLE `settings` (
    `id` INT AUTO_INCREMENT PRIMARY KEY,
    `setting_key` VARCHAR(100) NOT NULL UNIQUE,
    `setting_value` TEXT DEFAULT NULL,
    `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

### Connection Flow
```
app/Controllers/HomeController.php
    ↓
app/Models/Settings.php (uses)
    ↓
core/Database.php (singleton)
    ↓
configs/database.php (credentials)
    ↓
MySQL cgv_booking database
```

### Fallback Strategy
```
REQUEST FOR featured_movie_id
    ↓
SELECT FROM settings table
    ↓
IF FOUND → RETURN featured_movie_id
IF NOT FOUND → SELECT MIN(id) FROM movies
    ↓
CACHE IN SETTINGS FOR NEXT REQUEST
    ↓
RETURN featured_movie_id
```

---

## Setup Instructions

### Prerequisites
- MySQL server running
- Database `cgv_booking` created
- User `root` with appropriate permissions
- PHP with PDO MySQL support

### Step 1: Import Schema
```bash
mysql -u root -p cgv_booking < database/schema.sql
```
This creates all tables, including settings.

### Step 2: Initialize Settings
```bash
mysql -u root -p cgv_booking < database/init-settings.sql
```
This prepares the settings table and featured movie.

### Step 3: Verify Setup (Optional)
```bash
php test-settings.php
```
This runs comprehensive tests to ensure everything works.

---

## Usage in Code

### Via Settings Model
```php
require_once 'app/Models/Settings.php';

$settings = new Settings();

// Get featured movie ID (with automatic fallback)
$featured_id = $settings->getFeaturedMovieId();

// Get any setting by key
$value = $settings->getByKey('featured_movie_id');

// Update a setting
$settings->set('featured_movie_id', 5);
```

### Direct Query (Performance)
```php
$db = Database::getInstance()->getPdo();

$stmt = $db->prepare("
    SELECT id FROM movies WHERE id = (
        SELECT COALESCE(
            (SELECT setting_value FROM settings WHERE setting_key = ?),
            (SELECT MIN(id) FROM movies)
        )
    )
");
$stmt->execute(['featured_movie_id']);
$featured_id = $stmt->fetchColumn();
```

### In HomeController (Task 2)
```php
class HomeController extends Controller {
    public function index() {
        $settings = new Settings();
        $featured_movie_id = $settings->getFeaturedMovieId();
        
        $data = [
            'featured_movie_id' => $featured_movie_id,
            'featured_movie' => Movie::findById($featured_movie_id)
        ];
        
        view('home/index', $data);
    }
}
```

---

## Verification Queries

### Check Table Exists
```sql
SELECT COUNT(*) FROM information_schema.TABLES 
WHERE TABLE_NAME='settings';
-- Expected: 1
```

### Get Featured Movie ID
```sql
SELECT setting_value FROM settings 
WHERE setting_key='featured_movie_id';
-- Expected: valid movie ID
```

### Get Featured Movie (with Fallback)
```sql
SELECT m.* FROM movies m
WHERE m.id = (
    SELECT COALESCE(
        (SELECT setting_value FROM settings WHERE setting_key='featured_movie_id'),
        (SELECT MIN(id) FROM movies)
    )
)
LIMIT 1;
-- Expected: 1 movie row with all details
```

---

## Database Configuration

**File**: `configs/database.php`

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'cgv_booking');
define('DB_USER', 'root');
define('DB_PASS', ''); // Update if password needed
```

⚠️ **Important**: Update `DB_PASS` if your MySQL server requires a password.

---

## Error Handling

### Common Issues

**Issue**: "Settings table not found"
- **Cause**: Script ran before schema.sql was imported
- **Solution**: Run `database/schema.sql` first

**Issue**: "No featured movie set"
- **Cause**: No movies in database
- **Solution**: Run `database/init-settings.sql` to create sample movie

**Issue**: PDOException on connection
- **Cause**: Database not running or credentials wrong
- **Solution**: Check DB_HOST, DB_USER, DB_PASS in `configs/database.php`

---

## File Locations

```
project-root/
├── app/
│   └── Models/
│       └── Settings.php              ← NEW
├── database/
│   ├── schema.sql                    (includes settings table)
│   ├── init-settings.sql             ← NEW
│   └── SETTINGS_SETUP.md             ← NEW
├── configs/
│   └── database.php                  (database credentials)
├── core/
│   ├── Database.php                  (PDO connection)
│   └── Model.php                     (base model class)
├── test-settings.php                 ← NEW (verification)
└── .sisyphus/
    └── notepads/
        └── homepage-rewrite/
            ├── issues.md             (UPDATED)
            └── learnings.md          (UPDATED)
```

---

## Implementation Checklist

- [x] Settings table defined in schema.sql
- [x] Settings model created with 3 methods
- [x] Initialization script prepared
- [x] Fallback logic implemented
- [x] Test script comprehensive
- [x] Documentation complete
- [x] Code properly parameterized (no SQL injection)
- [x] PDO connection verified
- [x] Notepad documentation updated
- [x] Ready for Task 2 (controller)

---

## Next Steps (Task 2)

Task 2 will implement the HomeController that:
1. Instantiates Settings model
2. Gets featured_movie_id
3. Queries Movie model for details
4. Passes data to view

The Settings implementation provides:
- ✅ Database connectivity layer
- ✅ Fallback logic
- ✅ Clean API for retrieving settings
- ✅ Test suite for verification

---

## Support Resources

- **Setup Guide**: `database/SETTINGS_SETUP.md`
- **Test Script**: `php test-settings.php`
- **Notepad Documentation**: `.sisyphus/notepads/homepage-rewrite/`
- **Example Usage**: See "Usage in Code" section above

---

## Appendix: Key Decisions

### Why PDO instead of MySQLi?
- Aligns with existing `Database.php` pattern
- More secure (prepared statements)
- Better OOP design
- PDO already required by codebase

### Why Upsert instead of separate insert/update?
- Handles edge cases (settings already exists)
- Simpler controller code
- Atomic operation

### Why fallback to first movie?
- Ensures featured movie always available
- Graceful degradation if settings missing
- Better UX than 404 error

### Why getFeaturedMovieId() auto-caches?
- Performance optimization
- Reduces subsequent queries
- Only happens if settings row missing

---

**Document Version**: 1.0  
**La
