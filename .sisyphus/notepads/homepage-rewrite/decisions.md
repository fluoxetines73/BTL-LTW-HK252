# Decisions & Architecture Notes

## Featured Movie Data Source
**Decision**: Use `settings` table with fallback to first active movie
- Query: `SELECT setting_value FROM settings WHERE setting_key = 'featured_movie_id'`
- Fallback: `SELECT id FROM movies WHERE status='now_showing' ORDER BY release_date DESC LIMIT 1`
- Reason: Admin-friendly, no need to modify movie records

## Swiper.js via CDN
**Decision**: Use CDN (cdn.jsdelivr.net) instead of npm
- Reason: Lightweight, no build step, already works with PHP
- Version: 11.x latest
- Fallback: Check `window.Swiper` in JS before initializing

## Responsive Strategy
**Decision**: Mobile-first CSS with Bootstrap 5 grid
- Base styles for 375px (mobile)
- Media queries for 768px, 1024px breakpoints
- Use Bootstrap `col-sm-12`, `col-md-6`, `col-lg-3` instead of custom classes
- CSS Variables for all colors (no hardcoded hex values)

## Newsletter (Client-Side Only)
**Decision**: No backend API, no database storage
- Validation happens in browser
- Success message resets after 3 seconds
- No form submission (prevent default + show message)

## Carousel Slide Counts (Responsive)
- **375px (Mobile)**: 1 slide per view
- **768px (Tablet)**: 2-3 slides per view
- **1024px (Desktop)**: 4-5 slides per view
- Auto-play: 4-5 second interval, pause on hover

## View Component Approach
- Not using PHP traits or inheritance (keep simple)
- Components are plain PHP files with data passed via variables
- `include 'path/to/component.php'` inside main homepage view

## Data Fetching Queries
- All queries kept in HomeController (no separate Model)
- Raw MySQLi queries preferred (consistent with existing code)
- No pagination UI (fixed-size datasets: 8 recommendations, 6 coming-soon, 4 news, 5-7 genres)
