# TMDB Movie Seed Generator

PHP scripts that fetch movies releasing in Vietnam during May–June 2026 from **The Movie Database (TMDB)** and generate an SQL seed file matching `database/schema.sql`.

## Files

| File | Purpose |
|------|---------|
| `config.php` | API base URL, date range, region, output path |
| `TMDBClient.php` | Simple cURL wrapper for TMDB API v3 |
| `generate-movie-seed.php` | Main script: discovers movies, fetches details, writes SQL |

## Requirements

- PHP 8.x with **curl** and **json** extensions
- A free TMDB API key ([get one here](https://www.themoviedb.org/settings/api))

## Usage

```bash
# 1. Set your TMDB API key
export TMDB_API_KEY=your_api_key_here

# 2. Run the generator
php movie-seed-gen/generate-movie-seed.php

# 3. Import the generated seed
mysql -u root -p cgv_booking < database/movie_seed_may_jun_2026.sql
```

> **Windows (PowerShell):** `$env:TMDB_API_KEY="your_key"` then `php movie-seed-gen\generate-movie-seed.php`

## What It Does

1. **Discovers** movies via `/discover/movie` with:
   - `region=VN`
   - `release_date.gte=2026-05-01` & `release_date.lte=2026-06-30`
   - `with_release_type=2|3` (theatrical only)
   - `with_runtime.gte=60` (feature films)

2. **Fetches details** for each movie (`/movie/{id}`) with `append_to_response=credits,videos,release_dates` to get:
   - Title, overview, runtime, poster, backdrop
   - Director & top 5 cast members
   - YouTube trailer URL
   - Vietnam (or US fallback) certification → mapped to `P|C13|C16|C18`
   - Genres → mapped to existing `genres` table IDs from `seed.sql`

3. **Downloads images** to `public/uploads/movies/`:
   - Posters: downloaded at `w500` resolution
   - Banners/backdrops: downloaded at `w1280` resolution
   - Stored with unique filenames like `tmdb_poster_{hash}.jpg`
   - Only the filename is stored in the database (not full URLs)

4. **Generates SQL** (`database/movie_seed_may_jun_2026.sql`) with:
   - `DELETE FROM movie_genres` - clears all genre mappings
   - `DELETE FROM movies` - clears all existing movies
   - `ALTER TABLE movies AUTO_INCREMENT = 1` - resets ID counter
   - `INSERT INTO movies (...)` - inserts new movies starting from ID 1
   - `INSERT INTO movie_genres (...)` - inserts genre mappings

## Note: Complete Reset

The generated SQL **clears all existing movies** and resets IDs to start from 1. This ensures a clean slate with only the TMDB-fetched movies in your database.

**Warning:** Running the generated SQL will delete all existing movies and their genre mappings. Make sure to back up your data if you have movies you want to keep.

## Data Mappings

| TMDB Field | Schema Field | Notes |
|------------|--------------|-------|
| `title` | `title` | Vietnamese title when available |
| `overview` | `description` | NULL if empty |
| `credits.crew` (job=Director) | `director` | NULL if unknown |
| `credits.cast` (top 5) | `cast` | Comma-separated |
| `runtime` | `duration_min` | Defaults to 90 if unknown |
| `release_dates[VN]` or `release_date` | `release_date` | Prefers Vietnam theatrical date |
| `poster_path` | `poster` | Downloaded to `public/uploads/movies/`, stores filename only |
| `backdrop_path` | `banner` | Downloaded to `public/uploads/movies/`, stores filename only |
| `videos` (YouTube Trailer) | `trailer_url` | NULL if no trailer |
| `release_dates` certification | `age_rating` | VN cert → `P/C13/C16/C18`; falls back to US MPAA |
| `original_language` | `language` + `subtitle` | e.g. `Tieng Anh` / `Phu de Viet` |
| `production_countries` | `country` | Mapped to Vietnamese names (`My`, `Nhat Ban`, …) |
| `genres` | `movie_genres` | Mapped to existing genre IDs (1–10) |

## Troubleshooting

- **Images not displaying:** The script downloads images to `public/uploads/movies/`. Ensure this directory is writable by PHP. The database stores only filenames (e.g., `tmdb_poster_abc123.jpg`), not full URLs.
- **Rate limit (HTTP 429):** The script sleeps 250 ms between detail requests. If you still hit limits, increase `usleep(250000)` in `generate-movie-seed.php`.
- **No movies found:** TMDB may not have Vietnam release dates for many future films. Try broadening `date_from`/`date_to` in `config.php` or removing `with_release_type`.
- **Missing certifications:** Vietnam ratings are not officially supported by TMDB yet (as of 2025). The script falls back to US MPAA ratings (`G` → `P`, `PG-13` → `C13`, `R` → `C16`, `NC-17` → `C18`). Review the output SQL and adjust manually if needed.
