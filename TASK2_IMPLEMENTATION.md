# Task 2: HomeController Data Fetching Logic - COMPLETED ✅

## Summary
Extended `app/Controllers/HomeController.php::index()` method with complete data fetching logic for homepage.

## Implementation Checklist

### ✅ Data Fetching (All 6 Implemented)
- [x] **Featured Movie**: From settings with fallback to first active movie
- [x] **8 Recommended Movies**: WHERE status='now_showing' ORDER BY release_date DESC LIMIT 8
- [x] **Top 5-7 Genres**: JOIN with movie_genres, COUNT movies per genre, ORDER BY count DESC LIMIT 7
- [x] **6 Coming Soon Movies**: WHERE status='coming_soon' AND release_date >= CURDATE() ORDER BY release_date ASC LIMIT 6
- [x] **4 Recent News Items**: WHERE status='published' ORDER BY published_at DESC LIMIT 4
- [x] **5 Static Ads**: Hard-coded array with id, title, image, link, description

### ✅ Technical Requirements
- [x] PDO prepared statements used for all queries (SQL injection prevention)
- [x] Settings model integration (getFeaturedMovieId with fallback)
- [x] All data passed as associative arrays to view
- [x] Data structure: `$this->view('layouts/main', $data)`
- [x] Database::getInstance()->getPdo() for connection
- [x] No Model classes created (raw queries only)
- [x] Exact database columns from schema
- [x] Inline comments on complex joins

### ✅ Code Quality
- [x] PHP syntax verified (no errors/warnings)
- [x] LSP diagnostics: 0 errors
- [x] Following existing controller pattern
- [x] File: `app/Controllers/HomeController.php`

## Data Structure Passed to View

```php
[
    'title' => 'Trang chu',
    'content' => 'home/index',
    'featured_movie' => [
        'id' => int,
        'title' => string,
        'slug' => string,
        'description' => text,
        'poster' => string,
        'banner' => string,
        'release_date' => date,
        'duration_min' => int,
        'age_rating' => enum,
        'director' => string
    ],
    'recommendations' => [
        // 8 movies with same structure
    ],
    'genres' => [
        [
            'id' => int,
            'name' => string,
            'slug' => string,
            'movie_count' => int
        ],
        // 5-7 genres
    ],
    'coming_soon' => [
        // 6 movies with same structure as recommendations
    ],
    'news' => [
        [
            'id' => int,
            'title' => string,
            'slug' => string,
            'content' => text,
            'image' => string,
            'category' => enum,
            'published_at' => datetime,
            'status' => enum
        ],
        // 4 news items
    ],
    'ads' => [
        [
            'id' => int,
            'title' => string,
            'image' => string,
            'link' => string,
            'description' => string
        ],
        // 5 ads
    ]
]
```

## Database Queries

### 1. Featured Movie
```sql
SELECT id, title, slug, description, poster, banner, release_date, 
       duration_min, age_rating, director
FROM movies 
WHERE id = ? AND status IN ('now_showing', 'coming_soon')
```

### 2. Recommended Movies (8)
```sql
SELECT id, title, slug, description, poster, banner, release_date, 
       duration_min, age_rating, status
FROM movies 
WHERE status = 'now_showing'
ORDER BY release_date DESC
LIMIT 8
```

### 3. Top Genres with Count (5-7)
```sql
SELECT g.id, g.name, g.slug, COUNT(mg.movie_id) as movie_count
FROM genres g
LEFT JOIN movie_genres mg ON g.id = mg.genre_id
LEFT JOIN movies m ON mg.movie_id = m.id AND m.status IN ('now_showing', 'coming_soon')
GROUP BY g.id, g.name, g.slug
ORDER BY movie_count DESC
LIMIT 7
```

### 4. Coming Soon Movies (6)
```sql
SELECT id, title, slug, description, poster, banner, release_date, 
       duration_min, age_rating, status
FROM movies 
WHERE status = 'coming_soon' AND release_date >= CURDATE()
ORDER BY release_date ASC
LIMIT 6
```

### 5. Recent News (4)
```sql
SELECT id, title, slug, content, image, category, published_at, status
FROM news 
WHERE status = 'published'
ORDER BY published_at DESC
LIMIT 4
```

### 6. Static Ads
5 hard-coded ad objects with properties: id, title, image, link, description

## Integration Notes

### For View Files (`app/Views/home/index.php`)
All variables are extracted and available directly:
```php
<?php foreach ($recommendations as $movie): ?>
    <div><?php echo $movie['title']; ?></div>
<?php endforeach; ?>

<?php if ($featured_movie): ?>
    <h1><?php echo $featured_movie['title']; ?></h1>
<?php endif; ?>
```

### Dependencies
- ✅ Task 1 (Settings model) - COMPLETED
- ✅ Database schema (movies, genres, movie_genres, news) - EXISTS
- ✅ Core files (Database, Controller) - EXISTS

### Blocks
- → Tasks 5-11 (All homepage sections depend on this data)

## Files Modified
- `app/Controllers/HomeController.php` - index() method enhanced

## Files NOT Modified (Per Requirements)
- ✅ No Settings table structure modified
- ✅ No other controller methods touched
- ✅ No View files modified
- ✅ No API endpoints added
- ✅ No Model classes created (except Settings from Task 1)
- ✅ No caching added

## Verification
- PHP syntax: ✅ PASS (php -l clean)
- LSP diagnostics: ✅ PASS (0 errors)
- Git status: M app/Controllers/HomeController.php
- Ready for: next tasks (Tasks 5-11)
