# Homepage Rewrite: Skeleton to Fully Functional

## TL;DR

> **Quick Summary**: Transform the PHP MVC homepage from a skeleton to a feature-rich movie streaming platform with carousels, featured content hero, genre filters, newsletter signup, and news preview sections. All data fetched from live MySQL database with responsive design (768px/1024px breakpoints).
> 
> **Deliverables**:
> - Fully functional homepage with 3 carousels (Swiper.js)
> - Featured movie hero section (admin-controlled via settings table)
> - Genre filter bar (top 5-7 genres)
> - Newsletter signup banner (client validation)
> - News/promotions preview section
> - Responsive design (mobile-first, 768px/1024px breakpoints)
> - All components styled with Bootstrap 5 + CSS variables
> 
> **Estimated Effort**: Medium | **Parallel Execution**: YES (4-5 waves) | **Critical Path**: Task 1 → Task 3 → Task 5 → Task 8

---

## Context

### Original Request
User wants to rewrite the homepage from a basic skeleton into a fully functional, modern movie streaming platform landing page with:
- Movie recommendation slider
- Ads banner with carousel
- News/promotions sneak peek with CTAs
- Additional recommended features based on codebase analysis

### Interview Summary & Decisions

**Design Preferences Chosen**:
- Aesthetic: Modern & Minimal (clean, whitespace-focused, subtle animations)
- Performance Priority: Balanced (good animations + reasonable load time)
- Responsiveness: Truly Responsive (equal experience across all devices)

**Features Confirmed**:
1. **Featured Movie Hero** → Large spotlight section with banner, title, description, age rating, watch button (admin-controlled via settings table)
2. **Movie Recommendation Carousel** → 8 movies (now_showing, latest first) with Swiper.js
3. **Ads Banner Carousel** → Rotating ads with navigation dots
4. **Genre Filters** → Top 5-7 clickable genre chips linking to `/movies/current?genre=X`
5. **Coming Soon Carousel** → 6 upcoming movies with countdown/release dates
6. **Newsletter Signup Banner** → Email input with client-side validation (no backend storage)
7. **News/Promotions Preview** → 4 recent news items with image, title, date, "View All News" CTA
8. **Truly Responsive** → Mobile (375px), Tablet (768px), Desktop (1024px+)

**Research Findings**:
- Current stack: PHP MVC with Bootstrap 5 (CDN), MySQL database, CSS variables for branding
- No carousel libraries currently installed; **Swiper.js recommended** (via CDN, supports touch/swipe, arrows, dots)
- Database schema ready: `movies` table (id, title, poster, banner, description, release_date, status, age_rating, etc.), `genres` table with join table `movie_genres`, `news` table, `settings` table
- Existing pattern: Controller → array data → view loops (straightforward, no complex state management)
- Responsive breakpoints established: 768px (mobile/tablet switch), 1024px (tablet/desktop switch)
- CSS variables defined: `--cgv-red`, `--cgv-dark`, `--cgv-white`, `--transition-smooth`

**Metis Review Findings**:
- 3 critical blockers identified and resolved:
  1. **Data Source**: ✅ Use live database queries (not seeded data)
  2. **Featured Movie**: ✅ Query from settings table (admin-controlled), fallback to first active movie
  3. **Newsletter**: ✅ Client-side validation only (no backend storage or API)
- Scope guardrails established (must-haves vs. must-nots)
- Database queries planned (recommendations, hero, genres, coming soon, news)
- Edge cases identified (empty states, CDN fallback, filter URL handling)

---

## Work Objectives

### Core Objective
Create a fully functional, modern homepage for a movie streaming platform by transforming the skeleton `app/Views/home/index.php` into a feature-rich landing page with multiple interactive carousels, featured content, genre filters, and calls-to-action. All functionality integrated with live MySQL database, responsive design, and Bootstrap 5 styling.

### Concrete Deliverables
1. **Modified Controller** (`app/Controllers/HomeController.php`): Add data fetching logic (featured movie, carousels, genres, news)
2. **New Homepage View** (`app/Views/home/index.php`): Complete redesign with all sections
3. **Optional View Components** (`app/Views/components/`): Reusable carousel, hero, filter, newsletter components
4. **CSS Enhancements** (`public/css/home.css` or extended `style.css`): Responsive styling, carousel/section customization
5. **JavaScript** (in main layout or `public/js/home.js`): Swiper initialization, form validation, event handling
6. **Database Changes**: Create/confirm `settings` table for featured movie ID

### Definition of Done (Hard Stop Conditions)

- [ ] **Zero Console Errors**: Browser console clean of JS errors on homepage
- [ ] **All Carousels Functional**: Swiper initialized, prev/next navigation works, responsive slides (1 mobile, 2-3 tablet, 4-5 desktop)
- [ ] **All Sections Render**: Hero, 3 carousels, filters, newsletter, news preview all visible without errors
- [ ] **Responsive at Breakpoints**: Tested and responsive at 375px (mobile), 768px (tablet), 1024px+ (desktop)
- [ ] **Genre Filters Navigate**: Clicking genre links to `/movies/current?genre=ACTION` (or similar)
- [ ] **Newsletter Form Validates**: Empty email shows error, invalid email shows error, valid email shows success (client-side)
- [ ] **News Section Renders**: 4 recent news items displayed, "View All News" button links to `/news`
- [ ] **No Database Schema Changes**: Uses existing tables only (movies, genres, movie_genres, news, settings)
- [ ] **Manual QA Pass**: Tested on Chrome, Firefox, Edge (desktop) and iOS Safari, Android Chrome (mobile)

### Must Have (Non-Negotiable)

1. ✅ **Featured Movie Hero Section**
   - Displays featured movie poster/banner (admin-controlled via settings table)
   - Shows title, description, age rating, release date
   - "Book Now" / "Watch Now" CTA button

2. ✅ **Movie Recommendation Carousel**
   - Displays 8 now_showing movies using Swiper.js
   - Shows movie poster, title, rating on hover
   - Prev/Next navigation buttons
   - Pagination dots
   - Auto-play carousel with pause-on-hover

3. ✅ **Ads Banner Carousel**
   - Rotating banner images (e.g., 3-5 promotional ads)
   - Navigation arrows and dots
   - Responsive sizing (full-width desktop, full-width mobile)

4. ✅ **Genre Filter Bar**
   - Display top 5-7 genres with movie count
   - Clickable genre chips/buttons
   - Link to `/movies/current?genre=GENRE_NAME`
   - Active/highlighted state on current genre
   - Responsive wrapping on mobile

5. ✅ **Coming Soon Carousel**
   - Display 6 coming_soon movies with Swiper.js
   - Show release date (countdown or relative date)
   - Responsive carousel (same breakpoints as recommendations)

6. ✅ **Newsletter Signup Banner**
   - Email input field + "Subscribe" button
   - Client-side email validation (basic regex pattern)
   - Show error message for invalid/empty emails
   - Show success message on valid email submit
   - NO backend storage or API call

7. ✅ **News/Promotions Preview Section**
   - Display 4 most recent published news items
   - Show image, title, publish date, short excerpt
   - "View All News" button linking to `/news` page
   - Grid layout responsive (1 col mobile, 2 tablet, 4 desktop)

8. ✅ **Responsive Design**
   - Mobile-first approach
   - Breakpoints: 375px (mobile small), 768px (tablet), 1024px (desktop)
   - No horizontal scrolling on any breakpoint
   - Touch/swipe support on mobile carousels (Swiper native)
   - Tap targets ≥48px on mobile

9. ✅ **Modern Minimal Aesthetics**
   - Clean whitespace and layout
   - Subtle animations and transitions (0.3s ease per existing --transition-smooth)
   - Consistent use of CSS variables (--cgv-red, --cgv-dark, --cgv-white)
   - Bootstrap 5 grid for layout consistency

### Must NOT Have (Guardrails)

- ❌ **Do NOT** touch authentication system (leave AuthController, middleware untouched)
- ❌ **Do NOT** modify database schema (use existing tables only; if settings table missing, create simple table, don't extend schema)
- ❌ **Do NOT** create admin panel for featured movie management (assume manual SQL update for now, or simple one-off admin logic)
- ❌ **Do NOT** implement backend email service or newsletter subscriber storage
- ❌ **Do NOT** change existing movie list pages (`/movies`, `/movies/current`, `/movies/coming-soon`)
- ❌ **Do NOT** add new Model classes (use raw queries in Controller or simple helper functions)
- ❌ **Do NOT** create new API endpoints (use traditional PHP routing only)
- ❌ **Do NOT** add pagination UI for carousels (fixed-size, no pagination controls)
- ❌ **Do NOT** implement database query optimization, caching, or indexing
- ❌ **Do NOT** add analytics tracking, event logging, or A/B testing
- ❌ **Do NOT** implement advanced accessibility features (WCAG AAA compliance not required, basic a11y only)
- ❌ **Do NOT** add SEO optimization (meta tags, structured data, canonical URLs)
- ❌ **Do NOT** create automated tests or test suites (manual QA only)
- ❌ **Do NOT** add animations beyond standard Swiper transitions and CSS hover effects

---

## Verification Strategy (MANDATORY)

> **ZERO HUMAN INTERVENTION** — ALL verification is agent-executed via Playwright (UI), tmux (CLI), and Bash (API/PHP).
> **Acceptance criteria requiring "user manually tests/confirms" are FORBIDDEN.**

### Test Decision
- **Infrastructure exists**: NO automated tests
- **Approach**: Agent-Executed QA Scenarios (manual-free verification)
- **QA Tools**:
  - **Playwright**: Browser-based testing (UI rendering, carousels, form validation, responsiveness)
  - **Bash (curl)**: API calls and database queries
  - **tmux (interactive_bash)**: PHP server operations if needed

### QA Policy (Every Task MUST Include These)

**Scenario: Happy Path** (feature works as designed)
- Playwright opens browser, navigates to homepage
- Verifies section renders without console errors
- Checks specific selectors, content, interactions
- Captures screenshots as evidence

**Scenario: Edge Case / Error Handling** (graceful failure)
- Tests missing data, invalid input, error states
- Verifies no crash, proper error message displayed
- Tests mobile/tablet/desktop responsiveness

**Evidence Capture**: All QA results saved to `.sisyphus/evidence/task-{N}-{scenario-slug}.{ext}`

### Verification Commands (Final)

```bash
# Server running check
curl -s http://localhost:8000 | grep -q "<title>" && echo "✅ Homepage loads"

# No console errors (Playwright will verify in QA scenarios)
# No PHP warnings/errors in response headers and body

# Database connectivity
php -r "mysqli_connect('localhost', 'root', '', 'cgv_cinema');" && echo "✅ DB connected"

# Responsive breakpoints verified via Playwright viewport tests
# (375px, 768px, 1024px widths)
```

---

## Execution Strategy

### Parallel Execution Waves

```
WAVE 1 (Start Immediately — Foundation & Data Layer):
├── Task 1: Create/verify settings table + featured movie seed [quick]
├── Task 2: Extend HomeController with data fetching logic [quick]
├── Task 3: Add Swiper.js CDN to main layout [quick]
└── Task 4: Create view components directory + skeleton [quick]

WAVE 2 (After Wave 1 — HTML Structure):
├── Task 5: Build homepage hero section (featured movie) [unspecified-high]
├── Task 6: Build movie recommendation carousel section [unspecified-high]
├── Task 7: Build ads banner carousel section [quick]
├── Task 8: Build genre filter bar section [quick]
├── Task 9: Build coming soon carousel section [unspecified-high]
└── Task 10: Build newsletter signup banner section [quick]

WAVE 3 (After Wave 2 — Integration & Polish):
├── Task 11: Build news preview section [unspecified-high]
├── Task 12: Add responsive CSS (breakpoints 768px, 1024px) [visual-engineering]
├── Task 13: Add Swiper.js initialization + carousel logic [quick]
└── Task 14: Add form validation + interactivity (JS) [quick]

WAVE 4 (After Wave 3 — QA & Finalization):
├── Task 15: Manual QA (browser testing, responsiveness) [unspecified-high]
├── Task 16: Fix edge cases (empty states, CDN fallback, errors) [deep]
└── Task 17: Git cleanup, final commit [git]

FINAL VERIFICATION (After All Tasks):
├── Task F1: Plan compliance audit (oracle)
├── Task F2: Code quality review (no console errors, no !important abuse) [unspecified-high]
├── Task F3: Real manual QA across devices [unspecified-high]
└── Task F4: Scope fidelity check (deliverables vs. guardrails) [deep]
```

### Dependency Matrix

| Task | Dependencies | Blocks | Wave |
|------|--------------|--------|------|
| **1** | None | 2 | 1 |
| **2** | 1 | 5-11 | 1 |
| **3** | None | 13 | 1 |
| **4** | None | 5-11 | 1 |
| **5** | 2, 4 | 15 | 2 |
| **6** | 2, 3, 4 | 15 | 2 |
| **7** | 2, 3, 4 | 15 | 2 |
| **8** | 2, 4 | 15 | 2 |
| **9** | 2, 3, 4 | 15 | 2 |
| **10** | 2, 4 | 15 | 2 |
| **11** | 2, 4 | 15 | 2 |
| **12** | 5-11 | 15 | 3 |
| **13** | 6, 7, 9, 3 | 15 | 3 |
| **14** | 10, 14 | 15 | 3 |
| **15** | 12, 13, 14 | 16 | 4 |
| **16** | 15 | 17, F1-F4 | 4 |
| **17** | 16 | F1-F4 | 4 |
| **F1** | 17 | - | FINAL |
| **F2** | 17 | - | FINAL |
| **F3** | 17 | - | FINAL |
| **F4** | 17 | - | FINAL |

### Agent Dispatch Summary

- **Wave 1 (4 tasks)**: `quick` × 3, `quick` × 1 → Simple setup, database, CDN link, directories
- **Wave 2 (7 tasks)**: `unspecified-high` × 4, `quick` × 3 → HTML structure, carousel markup, sections
- **Wave 3 (4 tasks)**: `visual-engineering` × 1, `quick` × 2, `unspecified-high` × 1 → CSS/styling, JS init
- **Wave 4 (3 tasks)**: `unspecified-high` × 1, `deep` × 1, `git` × 1 → QA, edge cases, commits
- **FINAL (4 parallel)**: `oracle` × 1, `unspecified-high` × 2, `deep` × 1 → Verification loop

---

## TODOs

### WAVE 1: Foundation & Data Layer

- [x] **1. Create/Verify Settings Table + Featured Movie Seed**

  **What to do**:
  - Check if `settings` table exists in database; if not, create with columns: `id`, `setting_key` (VARCHAR unique), `setting_value` (VARCHAR)
  - Create/update a settings row: `setting_key = 'featured_movie_id'`, `setting_value = 1` (or use first movie ID)
  - Verify schema is correct and query works: `SELECT setting_value FROM settings WHERE setting_key = 'featured_movie_id'`

  **Must NOT do**:
  - Don't modify any other tables' schema
  - Don't create users/permissions tables

  **Recommended Agent Profile**:
  - **Category**: `quick` — Database setup is straightforward, SQL-based, no business logic
    - Reason: Simple table creation and seed data insertion; no complex dependencies

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 1 (with Tasks 2, 3, 4)
  - **Blocks**: Tasks 5, 2 (hero section depends on settings table; controller needs to know settings table exists)
  - **Blocked By**: None (can start immediately)

  **References**:
  - `database/schema.sql` — Existing schema; check for settings table definition
  - `app/Models/Model.php` — Base model for queries if needed
  - Database credentials in env/config files

  **Acceptance Criteria**:
  - [ ] Settings table exists in database: `SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_NAME='settings'` returns 1
  - [ ] Can query featured movie ID: `SELECT setting_value FROM settings WHERE setting_key='featured_movie_id'` returns a valid movie ID
  - [ ] Fallback defined: If no settings row, controller defaults to first movie (or ID 1)

  **QA Scenarios**:

  \`\`\`
  Scenario: Settings table exists and can be queried
    Tool: Bash (PHP + mysqli)
    Steps:
      1. Run: php -r "
        $conn = new mysqli('localhost', 'root', '', 'cgv_cinema');
        $result = $conn->query('SELECT setting_value FROM settings WHERE setting_key=\"featured_movie_id\"');
        $row = $result->fetch_assoc();
        echo json_encode($row);
      "
    Expected Result: JSON output with valid movie ID (e.g., {"setting_value":"1"})
    Evidence: .sisyphus/evidence/task-1-settings-query.txt

  Scenario: Fallback works if settings row missing
    Tool: Bash (PHP script)
    Steps:
      1. Temporarily delete the settings row
      2. Run controller query with fallback: SELECT id FROM movies WHERE status='now_showing' ORDER BY release_date DESC LIMIT 1
      3. Verify query returns a movie ID
    Expected Result: Fallback query returns valid movie ID even without settings row
    Evidence: .sisyphus/evidence/task-1-fallback-test.txt
  \`\`\`

  **Commit**: YES
  - Message: `feat(database): create settings table and featured movie seed`
  - Files: `database/schema.sql` (if modified), migration SQL script
  - Pre-commit: `php -r "mysqli_connect(...)" && echo "✅ DB connection OK"`

---

- [x] **2. Extend HomeController with Data Fetching Logic**

  **What to do**:
  - Modify `app/Controllers/HomeController.php` → add/extend `index()` method to fetch:
    1. Featured movie (from settings table, fallback to first active movie)
    2. 8 recommended movies (WHERE status='now_showing' ORDER BY release_date DESC LIMIT 8)
    3. Top 5-7 genres (SELECT genres with movie count, from movie_genres join)
    4. 6 coming soon movies (WHERE status='coming_soon' AND release_date >= CURDATE() ORDER BY release_date ASC LIMIT 6)
    5. 4 recent news items (WHERE status='published' ORDER BY published_at DESC LIMIT 4)
    6. Ad data (create static array of 3-5 ads or query from ads table if exists)
  - Pass all data as associative arrays to `view('home/index', $data)`

  **Must NOT do**:
  - Don't create a Movie model class (use raw queries or simple helpers in controller)
  - Don't implement caching or optimization beyond basic LIMIT/WHERE
  - Don't modify AuthController or other controllers
  - Don't add any API endpoints

  **Recommended Agent Profile**:
  - **Category**: `quick` — Data fetching is straightforward SQL queries, no complex business logic
    - Reason: Simple SELECT queries with joins, similar patterns already exist in MoviesController; minimal controller logic

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 1 (with Tasks 1, 3, 4)
  - **Blocks**: Tasks 5-11 (all sections depend on data from controller)
  - **Blocked By**: Task 1 (settings table must exist first)

  **References**:
  - `app/Controllers/HomeController.php` — Existing controller structure; modify index() method
  - `app/Controllers/MoviesController.php:35-50` — Existing query patterns for seeded movie data
  - `database/schema.sql` — Table structure (movies, genres, movie_genres, news, settings, ads if exists)

  **Acceptance Criteria**:
  - [ ] HomeController::index() fetches and passes all 6 data sets (featured, recommendations, genres, coming_soon, news, ads)
  - [ ] No PHP errors or warnings when page loads
  - [ ] All data arrays passed to view: featured_movie, recommendations, genres, coming_soon, news, ads

  **QA Scenarios**:

  \`\`\`
  Scenario: All data fetches without errors
    Tool: Bash (curl)
    Steps:
      1. curl -s http://localhost:8000 | grep -o "<title>" (verify page loads)
      2. Run PHP script: include 'app/Controllers/HomeController.php'; $c = new HomeController(); $c->index(); echo "✅"
    Expected Result: No PHP errors in output, all data fetched successfully
    Evidence: .sisyphus/evidence/task-2-data-fetch.txt

  Scenario: Featured movie set correctly
    Tool: Bash (PHP)
    Steps:
      1. Query controller to retrieve featured_movie variable
      2. Verify it has: id, title, poster, banner, description, age_rating
    Expected Result: Featured movie array has all required fields
    Evidence: .sisyphus/evidence/task-2-featured-movie.json
  \`\`\`

  **Commit**: YES
  - Message: `feat(controller): add homepage data fetching logic`
  - Files: `app/Controllers/HomeController.php`
  - Pre-commit: `php -l app/Controllers/HomeController.php`

---

- [x] **3. Add Swiper.js CDN Link to Main Layout**

  **What to do**:
  - Edit `app/Views/layouts/main.php` → add Swiper.js CDN links in `<head>`:
    - Swiper CSS: `<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">`
    - Swiper JS: `<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>` (before closing `</body>` or in head)
  - Verify CDN is active (optional: add fallback check for offline mode)

  **Must NOT do**:
  - Don't install Swiper via npm (stay with CDN)
  - Don't modify any other scripts in layout
  - Don't add Swiper initialization here (will do in separate task)

  **Recommended Agent Profile**:
  - **Category**: `quick` — Single file edit, adding CDN links
    - Reason: Trivial change, no logic, no dependencies

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 1 (with Tasks 1, 2, 4)
  - **Blocks**: Tasks 6, 7, 9, 13 (carousels depend on Swiper library)
  - **Blocked By**: None

  **References**:
  - `app/Views/layouts/main.php` — Main layout file; add CDN links in head section
  - Swiper official docs: https://swiperjs.com/get-started

  **Acceptance Criteria**:
  - [ ] Swiper CSS and JS CDN links present in main.php head section
  - [ ] Browser can load page without CDN errors (verify in DevTools Network tab)
  - [ ] `window.Swiper` defined in browser console after page load

  **QA Scenarios**:

  \`\`\`
  Scenario: Swiper CDN loads successfully
    Tool: Playwright
    Steps:
      1. Navigate to http://localhost:8000
      2. Open DevTools console
      3. Execute: typeof window.Swiper === 'function' ? console.log('✅ Swiper loaded') : console.log('❌ Swiper not found')
    Expected Result: Console logs "✅ Swiper loaded"
    Evidence: .sisyphus/evidence/task-3-swiper-load.png

  Scenario: Swiper CSS loaded (no 404)
    Tool: Bash (curl)
    Steps:
      1. curl -I https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css | grep "HTTP"
    Expected Result: HTTP 200 OK (CDN responds successfully)
    Evidence: .sisyphus/evidence/task-3-swiper-css.txt
  \`\`\`

  **Commit**: YES
  - Message: `feat(layout): add Swiper.js CDN links`
  - Files: `app/Views/layouts/main.php`
  - Pre-commit: `grep -c "swiper" app/Views/layouts/main.php`

---

- [x] **4. Create View Components Directory + Skeleton Structure**

  **What to do**:
  - Create directory: `app/Views/components/`
  - Create skeleton files (empty or minimal structure for later tasks):
    - `app/Views/components/carousel.php` (template for reusable carousel)
    - `app/Views/components/hero.php` (featured movie hero)
    - `app/Views/components/filter-bar.php` (genre filters)
    - `app/Views/components/newsletter.php` (newsletter signup)
    - `app/Views/components/news-grid.php` (news preview)
  - Optional: Create `README.md` in components/ explaining the component pattern

  **Must NOT do**:
  - Don't pre-populate components with full HTML yet (will be done in tasks 5-11)
  - Don't create unnecessary component files

  **Recommended Agent Profile**:
  - **Category**: `quick` — Create directory and skeleton files
    - Reason: File system setup, no code logic

  **Parallelization**:
  - **Can Run In Parallel**: YES
  - **Parallel Group**: Wave 1 (with Tasks 1, 2, 3)
  - **Blocks**: Tasks 5-11 (components directory needed)
  - **Blocked By**: None

  **References**:
  - Existing view structure: `app/Views/home/`, `app/Views/admin/`, `app/Views/layouts/`

  **Acceptance Criteria**:
  - [ ] `app/Views/components/` directory created
  - [ ] All 5 skeleton component files exist and are readable

  **QA Scenarios**:

  \`\`\`
  Scenario: Component directory and files created
    Tool: Bash (ls)
    Steps:
      1. ls -la app/Views/components/
    Expected Result: Directory contains carousel.php, hero.php, filter-bar.php, newsletter.php, news-grid.php
    Evidence: .sisyphus/evidence/task-4-components-dir.txt
  \`\`\`

  **Commit**: YES
  - Message: `chore(components): create components directory structure`
  - Files: `app/Views/components/` (new files)
  - Pre-commit: `find app/Views/components -name "*.php" | wc -l`

---

### WAVE 2: HTML Structure & Sections

- [x] **5. Build Featured Movie Hero Section**

  **What to do**:
  - Create/populate `app/Views/home/index.php` with hero section:
    - Display featured movie banner image (poster or banner field from DB)
    - Show title, description, age_rating, release_date
    - Add "Book Now" / "Watch Now" CTA button linking to `/movies/{movie_id}` or booking page
    - Use Bootstrap row/col for responsive grid
    - Apply CSS classes for styling (will add CSS in task 12)
  - Hero should span full width on desktop, stack nicely on mobile
  - Use existing --cgv-* CSS variables

  **Must NOT do**:
  - Don't add styling/CSS (separate task 12)
  - Don't add JavaScript interactivity

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high` — Building semantic HTML structure with data binding
    - Reason: Requires understanding of data structure, Bootstrap grid patterns, responsive markup; moderate complexity

  **Parallelization**:
  - **Can Run In Parallel**: NO (depends on task 2 for data)
  - **Parallel Group**: Wave 2 start
  - **Blocks**: Task 12 (CSS depends on HTML structure)
  - **Blocked By**: Task 2

  **References**:
  - `app/Views/home/index.php` — File to create/modify (skeleton exists)
  - Featured movie data: `$featured_movie` from controller (array with id, title, poster, banner, description, age_rating)
  - Bootstrap grid: `row`, `col-md-*`, `img-fluid` classes
  - Existing pages for patterns: `app/Views/movies/index.php` or `current.php` (list layout with movie cards)

  **Acceptance Criteria**:
  - [ ] Hero section HTML renders without errors
  - [ ] Featured movie poster/banner displays
  - [ ] Title, description, age rating visible
  - [ ] CTA button present and links to correct URL
  - [ ] Responsive on mobile/tablet (no horizontal scroll)

  **QA Scenarios**:

  \`\`\`
  Scenario: Hero section renders with featured movie data
    Tool: Playwright
    Steps:
      1. Navigate to http://localhost:8000
      2. Look for hero section (first major section after header)
      3. Verify image displays: const img = document.querySelector('.hero img'); img ? console.log('✅ Image visible') : console.log('❌ No image');
      4. Verify title displays: const title = document.querySelector('.hero h1'); console.log(title.textContent.length > 0 ? '✅ Title' : '❌ No title')
      5. Verify CTA button: const btn = document.querySelector('.hero .btn'); console.log(btn ? '✅ Button found' : '❌ No button');
    Expected Result: All elements present and visible
    Evidence: .sisyphus/evidence/task-5-hero-section.png

  Scenario: Hero responsive on mobile
    Tool: Playwright
    Steps:
      1. Set viewport to 375px width
      2. Verify hero still visible without horizontal scroll
      3. Check image and text stack properly
    Expected Result: Hero responsive, no horizontal overflow
    Evidence: .sisyphus/evidence/task-5-hero-mobile.png
  \`\`\`

  **Commit**: NO (grouped with later tasks)

---

- [x] **6. Build Movie Recommendation Carousel Section**

  **What to do**:
  - Add carousel section to homepage HTML (`app/Views/home/index.php` after hero):
    - Title: "Recommended Movies" or similar
    - Display 8 movies in Swiper carousel
    - Show poster, title, rating on each slide
    - Add Swiper classes: `swiper`, `swiper-wrapper`, `swiper-slide`
    - Add navigation: prev button, next button, pagination dots
    - Use Bootstrap grid for outer container
  - Pass `$recommendations` array from controller
  - Loop through each movie: `<?php foreach ($recommendations as $movie): ?> ... <?php endforeach; ?>`

  **Must NOT do**:
  - Don't add Swiper initialization (task 13)
  - Don't add CSS styling (task 12)
  - Don't hardcode data

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high` — Complex carousel markup with Swiper structure
    - Reason: Requires understanding of Swiper's HTML structure, nested div patterns, navigation elements; moderate-high complexity

  **Parallelization**:
  - **Can Run In Parallel**: YES (with tasks 7, 8, 9, 10, 11)
  - **Parallel Group**: Wave 2 (depends on task 2, no cross-section dependencies)
  - **Blocks**: Task 13
  - **Blocked By**: Task 2, 3

  **References**:
  - Controller data: `$recommendations` (array of 8 movies)
  - Swiper HTML structure: https://swiperjs.com/swiper-api#swiper-html-structure
  - Movie fields: id, title, poster, rating, release_date

  **Acceptance Criteria**:
  - [ ] Carousel HTML renders with 8 movie slides
  - [ ] Each slide shows poster, title, rating
  - [ ] Swiper navigation buttons present (prev, next)
  - [ ] Pagination dots present

  **QA Scenarios**:

  \`\`\`
  Scenario: Recommendations carousel renders with all slides
    Tool: Playwright
    Steps:
      1. Find carousel section: const carousel = document.querySelector('.recommendations-carousel');
      2. Count slides: const slides = carousel.querySelectorAll('.swiper-slide'); console.log('Slides:', slides.length);
      3. Verify: slides.length === 8 ? console.log('✅ 8 slides found') : console.log('❌ Expected 8, got', slides.length);
    Expected Result: Exactly 8 slides in carousel
    Evidence: .sisyphus/evidence/task-6-carousel-slides.txt

  Scenario: Navigation elements present
    Tool: Playwright
    Steps:
      1. Check for prev button: document.querySelector('.recommendations-carousel .swiper-button-prev') ? console.log('✅ Prev button') : console.log('❌ No prev');
      2. Check for next button: document.querySelector('.recommendations-carousel .swiper-button-next') ? console.log('✅ Next button') : console.log('❌ No next');
      3. Check for pagination: document.querySelector('.recommendations-carousel .swiper-pagination') ? console.log('✅ Pagination') : console.log('❌ No pagination');
    Expected Result: All navigation elements present
    Evidence: .sisyphus/evidence/task-6-carousel-nav.txt
  \`\`\`

  **Commit**: NO (grouped with later tasks)

---

- [x] **7. Build Ads Banner Carousel Section**

  **What to do**:
  - Add ads carousel section to homepage HTML:
    - Title: "Promotions" or "Featured Ads"
    - Display rotating ad banners (3-5 ads from controller)
    - Large responsive banner images
    - Swiper carousel (similar to task 6)
    - Navigation arrows and dots
    - Ads should be prominent, eye-catching

  **Must NOT do**:
  - Don't add styling (task 12)
  - Don't add JavaScript initialization (task 13)

  **Recommended Agent Profile**:
  - **Category**: `quick` — Similar to task 6 but simpler (fewer elements per slide)
    - Reason: Straightforward carousel markup, minimal complexity

  **Parallelization**:
  - **Can Run In Parallel**: YES (with tasks 6, 8, 9, 10, 11)
  - **Parallel Group**: Wave 2
  - **Blocks**: Task 13
  - **Blocked By**: Task 2, 3

  **References**:
  - Controller data: `$ads` (array of 3-5 ads)
  - Ad fields: id, title, image, link (or similar)

  **Acceptance Criteria**:
  - [ ] Ads carousel renders with all ad items
  - [ ] Navigation and pagination present

  **QA Scenarios**:

  \`\`\`
  Scenario: Ads carousel displays and navigates
    Tool: Playwright
    Steps:
      1. Find ads carousel: const adsCarousel = document.querySelector('.ads-carousel');
      2. Count ads: const adSlides = adsCarousel.querySelectorAll('.swiper-slide');
      3. Verify slides render: adSlides.length >= 3 ? console.log('✅ Ads loaded') : console.log('❌ No ads');
    Expected Result: At least 3 ads visible in carousel
    Evidence: .sisyphus/evidence/task-7-ads-carousel.png
  \`\`\`

  **Commit**: NO (grouped with later tasks)

---

- [x] **8. Build Genre Filter Bar Section**

- [x] **9. Build Coming Soon Carousel Section**

  **What to do**:
  - Add coming soon carousel section (similar to task 6):
    - Title: "Coming Soon" or "Upcoming Releases"
    - Display 6 coming_soon movies in Swiper carousel
    - Show poster, title, release_date on each slide
    - Release date can be shown as countdown or relative date ("In 5 days")
    - Navigation and pagination

  **Must NOT do**:
  - Don't add styling or countdown animation (task 12/14)
  - Don't add JavaScript (task 13)

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high` — Similar carousel structure with date handling
    - Reason: Carousel markup + date formatting (PHP strtotime/relative date logic); moderate complexity

  **Parallelization**:
  - **Can Run In Parallel**: YES (with tasks 6, 7, 8, 10, 11)
  - **Parallel Group**: Wave 2
  - **Blocks**: Task 13
  - **Blocked By**: Task 2, 3

  **References**:
  - Controller data: `$coming_soon` (array of 6 movies)
  - Movie fields: id, title, poster, release_date

  **Acceptance Criteria**:
  - [ ] Coming soon carousel renders with 6 slides
  - [ ] Release dates display correctly
  - [ ] Navigation and pagination present

  **QA Scenarios**:

  \`\`\`
  Scenario: Coming soon carousel displays future movies
    Tool: Playwright
    Steps:
      1. Find coming soon carousel: const cominSoonCarousel = document.querySelector('.coming-soon-carousel');
      2. Count slides: const comingSlides = cominSoonCarousel.querySelectorAll('.swiper-slide'); console.log('Coming soon:', comingSlides.length);
      3. Verify dates: const dateElements = cominSoonCarousel.querySelectorAll('[data-release-date]'); dateElements.forEach(el => console.log('Date:', el.textContent));
    Expected Result: 6 slides with visible release dates
    Evidence: .sisyphus/evidence/task-9-coming-soon.png
  \`\`\`

  **Commit**: NO (grouped with later tasks)

---

- [x] **10. Build Newsletter Signup Banner Section**

- [x] **11. Build News Preview Section**

  **What to do**:
  - Add news preview grid section to homepage:
    - Title: "Latest News" or "News & Updates"
    - Display 4 recent news items in grid layout
    - Show image, title, publish date for each item
    - Add excerpt or short description (optional)
    - "View All News" button linking to `/news` page
    - Grid responsive: 1 column mobile, 2 tablet, 4 desktop

  **Must NOT do**:
  - Don't add styling (task 12)
  - Don't pagination (fixed 4 items)

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high` — Grid layout with multiple data items
    - Reason: Bootstrap grid structure with responsive columns, conditional rendering for optional fields; moderate complexity

  **Parallelization**:
  - **Can Run In Parallel**: YES (with tasks 6, 7, 8, 9, 10)
  - **Parallel Group**: Wave 2
  - **Blocks**: Task 12
  - **Blocked By**: Task 2

  **References**:
  - Controller data: `$news` (array of 4 news items)
  - News fields: id, title, slug, image, excerpt, category, published_at
  - Bootstrap grid: `row`, `col-md-6`, `col-lg-3`

  **Acceptance Criteria**:
  - [ ] News grid renders with 4 items
  - [ ] Each item shows image, title, date
  - [ ] "View All" button links to `/news`

  **QA Scenarios**:

  \`\`\`
  Scenario: News preview section displays 4 items
    Tool: Playwright
    Steps:
      1. Find news section: const newsSection = document.querySelector('.news-preview');
      2. Count items: const newsItems = newsSection.querySelectorAll('.news-item'); console.log('Items:', newsItems.length);
      3. Verify: newsItems.length === 4 ? console.log('✅ 4 items') : console.log('⚠️ Expected 4, got', newsItems.length);
      4. Check "View All" button: const viewAllBtn = newsSection.querySelector('a[href*="/news"]'); viewAllBtn ? console.log('✅ View All button found') : console.log('❌ No View All button');
    Expected Result: 4 news items + View All button visible
    Evidence: .sisyphus/evidence/task-11-news-preview.png
  \`\`\`

  **Commit**: NO (grouped with later tasks)

---

### WAVE 3: Integration & Polish

- [x] **12. Add Responsive CSS (Breakpoints 768px, 1024px)**

  **What to do**:
  - Create or extend CSS file: `public/css/home.css` (or add to `public/css/style.css`)
  - Add responsive styles for all homepage sections:
    - Hero section: Full-width responsive image, text centering
    - Carousels: Swiper slide sizing, navigation arrow positioning
    - Genre filters: Flex wrapping, responsive chip sizing
    - Newsletter: Full-width or constrained container, responsive inputs
    - News grid: Bootstrap responsive columns (col-sm-12, col-md-6, col-lg-3)
  - Implement breakpoints:
    - 375px (mobile small): 1 column, full-width
    - 768px (tablet): 2-3 columns, adjusted spacing
    - 1024px (desktop): 4-5 columns, wider layout
  - Use existing CSS variables: --cgv-red, --cgv-dark, --cgv-white, --transition-smooth
  - No hardcoded colors; leverage Bootstrap utilities

  **Must NOT do**:
  - Don't use `!important` unless absolutely necessary (Bootstrap override only)
  - Don't add animations (separate task 14)
  - Don't modify Bootstrap CDN or variables

  **Recommended Agent Profile**:
  - **Category**: `visual-engineering` — CSS design and responsive layouts
    - Reason: Visual design, responsive breakpoints, CSS variables, layout optimization; design-focused

  **Parallelization**:
  - **Can Run In Parallel**: NO (depends on HTML structure from tasks 5-11)
  - **Parallel Group**: Wave 3 start
  - **Blocks**: Task 15 (QA phase)
  - **Blocked By**: Tasks 5-11

  **References**:
  - Existing CSS: `public/css/style.css` (CSS variables, Bootstrap overrides)
  - CSS files: `public/assets/css/about.css`, `public/assets/css/faq.css` (responsive patterns)
  - Bootstrap 5 grid: https://getbootstrap.com/docs/5.0/layout/grid/

  **Acceptance Criteria**:
  - [ ] All homepage sections have responsive CSS for 375px, 768px, 1024px breakpoints
  - [ ] No hardcoded colors (use CSS variables)
  - [ ] CSS minified or well-organized
  - [ ] No layout shifts or horizontal scrolling on mobile

  **QA Scenarios**:

  \`\`\`
  Scenario: Responsive design at 375px (mobile small)
    Tool: Playwright
    Steps:
      1. Set viewport to 375px width, 812px height
      2. Navigate to http://localhost:8000
      3. Check for horizontal scrolling: document.documentElement.scrollWidth <= window.innerWidth ? console.log('✅ No horizontal scroll') : console.log('❌ Horizontal scroll detected');
      4. Verify hero stacks: const hero = document.querySelector('.hero'); const heroWidth = hero.offsetWidth; heroWidth <= 375 ? console.log('✅ Hero responsive') : console.log('❌ Hero too wide');
      5. Check carousels: document.querySelectorAll('.swiper').forEach(s => console.log('Swiper width:', s.offsetWidth));
    Expected Result: No horizontal scroll, all sections fit within 375px viewport
    Evidence: .sisyphus/evidence/task-12-responsive-375px.png

  Scenario: Responsive design at 1024px (desktop)
    Tool: Playwright
    Steps:
      1. Set viewport to 1440px width, 900px height (desktop)
      2. Navigate to homepage
      3. Check news grid columns: const newsGrid = document.querySelector('.news-preview .row'); const cols = newsGrid.querySelectorAll('[class*="col"]');
      4. Verify: cols[0].offsetWidth >= 300 ? console.log('✅ Desktop layout optimized') : console.log('❌ Columns too narrow');
    Expected Result: Layout optimized for desktop (wider columns, better spacing)
    Evidence: .sisyphus/evidence/task-12-responsive-1440px.png
  \`\`\`

  **Commit**: NO (grouped with task 13)

---

- [x] **13. Add Swiper.js Initialization + Carousel Logic**

  **What to do**:
  - Add JavaScript to initialize Swiper carousels (create `public/js/home.js` or add to main layout):
    - Initialize 3 Swiper instances for: recommendations, ads, coming_soon
    - Configure options:
      - Auto-play: enabled (3-5 second interval)
      - Pause on hover: yes
      - Loop: yes (infinite carousel)
      - Navigation: arrows + pagination dots
      - Responsive breakpoints:
        - 375px: 1 slide per view
        - 768px: 2-3 slides per view
        - 1024px: 4-5 slides per view
      - Touch/swipe enabled (Swiper default)
    - Add error handling: if Swiper CDN failed to load, show graceful fallback

  **Must NOT do**:
  - Don't add animations beyond Swiper transitions
  - Don't modify Swiper CSS

  **Recommended Agent Profile**:
  - **Category**: `quick` — JavaScript initialization of library
    - Reason: Swiper documentation is clear, standard initialization pattern; straightforward

  **Parallelization**:
  - **Can Run In Parallel**: NO (depends on carousel HTML from tasks 6, 7, 9 and Swiper CDN from task 3)
  - **Parallel Group**: Wave 3 start (after task 3)
  - **Blocks**: Task 15
  - **Blocked By**: Tasks 3, 6, 7, 9

  **References**:
  - Swiper documentation: https://swiperjs.com/swiper-api
  - Swiper responsive breakpoints: https://swiperjs.com/swiper-api#breakpoints
  - Existing JS patterns in `public/js/app.js`

  **Acceptance Criteria**:
  - [ ] 3 Swiper instances initialized (no console errors)
  - [ ] Carousels auto-play correctly
  - [ ] Prev/next buttons navigate carousels
  - [ ] Pagination dots functional
  - [ ] Responsive breakpoints working (correct slide count per breakpoint)

  **QA Scenarios**:

  \`\`\`
  Scenario: Swiper initialization successful
    Tool: Playwright
    Steps:
      1. Navigate to homepage
      2. Check console: const swipers = window.Swiper.instances; console.log('Swiper instances:', swipers ? swipers.length : 'undefined');
      3. Verify at least 3 initialized: swipers && swipers.length >= 3 ? console.log('✅ All carousels initialized') : console.log('❌ Missing carousels');
    Expected Result: 3+ Swiper instances running, no console errors
    Evidence: .sisyphus/evidence/task-13-swiper-init.txt

  Scenario: Carousel navigation works
    Tool: Playwright
    Steps:
      1. Find first carousel: const firstCarousel = document.querySelector('.swiper');
      2. Click next button: firstCarousel.querySelector('.swiper-button-next').click();
      3. Wait 500ms for animation
      4. Verify slide changed: firstCarousel.querySelector('.swiper-slide-active').textContent !== originalSlide ? console.log('✅ Navigation works') : console.log('❌ No slide change');
    Expected Result: Carousel advances to next slide on button click
    Evidence: .sisyphus/evidence/task-13-carousel-nav-click.png

  Scenario: Responsive breakpoints apply correctly
    Tool: Playwright
    Steps:
      1. Set viewport to 375px
      2. Find carousel: const carousel = document.querySelector('.swiper');
      3. Check slides visible: const visibleSlides = carousel.querySelectorAll('.swiper-slide:not(.swiper-slide-hidden)');
      4. Verify 1 slide: visibleSlides.length === 1 ? console.log('✅ Mobile: 1 slide/view') : console.log('❌ Expected 1, got', visibleSlides.length);
      5. Set viewport to 1024px, repeat: should show 4-5 slides
    Expected Result: Carousel shows correct number of slides per breakpoint
    Evidence: .sisyphus/evidence/task-13-responsive-breakpoints.txt
  \`\`\`

  **Commit**: NO (grouped with task 14)

---

- [x] **14. Add Form Validation + Interactivity (JavaScript)**

  **What to do**:
  - Add JavaScript for form validation and interactions:
    - Newsletter email validation:
      - Check for empty input (show "Email required" error)
      - Validate email format with regex: `/^[^\s@]+@[^\s@]+\.[^\s@]+$/`
      - On valid submit: show success message "Thanks for subscribing!"
      - On invalid: show error message "Please enter a valid email"
    - Optional: Add smooth scrolling for genre filter clicks (if desired)
    - Add CDN fallback for Swiper (if CDN fails, show message or static fallback)

  **Must NOT do**:
  - Don't send form data to backend (client-side only)
  - Don't store emails
  - Don't add complex animations

  **Recommended Agent Profile**:
  - **Category**: `quick` — Form validation and message handling
    - Reason: Basic regex validation, DOM manipulation, event listeners; straightforward

  **Parallelization**:
  - **Can Run In Parallel**: NO (depends on form HTML from task 10, carousel JS from task 13)
  - **Parallel Group**: Wave 3 (after tasks 10, 13)
  - **Blocks**: Task 15
  - **Blocked By**: Tasks 10, 13

  **References**:
  - Newsletter form selector and structure from task 10
  - Email regex pattern (standard web validation)

  **Acceptance Criteria**:
  - [ ] Newsletter validation works (empty email shows error)
  - [ ] Invalid email format rejected
  - [ ] Valid email shows success message
  - [ ] No console errors

  **QA Scenarios**:

  \`\`\`
  Scenario: Newsletter validation (empty email)
    Tool: Playwright
    Steps:
      1. Find email input: const emailInput = document.querySelector('input[type="email"][name="email"]');
      2. Leave empty, click subscribe: document.querySelector('.newsletter-banner button').click();
      3. Wait for error message
      4. Check error displays: const errorMsg = document.querySelector('.newsletter-banner .error-message'); errorMsg && errorMsg.textContent ? console.log('✅ Error shown:', errorMsg.textContent) : console.log('❌ No error message');
    Expected Result: Error message appears for empty email
    Evidence: .sisyphus/evidence/task-14-validation-empty.png

  Scenario: Newsletter validation (invalid email)
    Tool: Playwright
    Steps:
      1. Type invalid email: emailInput.value = 'notanemail';
      2. Click subscribe button
      3. Check error: const errorMsg = document.querySelector('.error-message'); errorMsg ? console.log('✅ Invalid email rejected:', errorMsg.textContent) : console.log('❌ No error');
    Expected Result: Invalid email rejected with error message
    Evidence: .sisyphus/evidence/task-14-validation-invalid.png

  Scenario: Newsletter validation (valid email)
    Tool: Playwright
    Steps:
      1. Type valid email: emailInput.value = 'test@example.com';
      2. Click subscribe button
      3. Wait for success message: const successMsg = document.querySelector('.success-message'); 
      4. Verify: successMsg && successMsg.textContent ? console.log('✅ Success shown:', successMsg.textContent) : console.log('❌ No success message');
    Expected Result: Valid email shows success message
    Evidence: .sisyphus/evidence/task-14-validation-valid.png
  \`\`\`

  **Commit**: YES
  - Message: `feat(homepage): complete homepage sections, styling, and interactivity`
  - Files: `app/Views/home/index.php`, `public/css/home.css` (or style.css), `public/js/home.js`
  - Pre-commit: `php -l app/Views/home/index.php && grep -c "swiper" public/js/home.js && echo "✅ All files valid"`

---

### WAVE 4: Quality Assurance & Finalization

- [x] **15. Manual QA (Browser Testing, Responsiveness)**

  **What to do**:
  - Comprehensive manual testing across devices and browsers:
    - **Desktop (1920px, 1440px)**:
      - Chrome, Firefox, Edge: Check all sections render correctly
      - No console JavaScript errors
      - Carousels navigate smoothly (prev/next, pagination dots)
      - Genre filters clickable, links correct
      - Newsletter form validates correctly
      - Images load without 404s
    - **Tablet (768px)**:
      - iPad/tablet viewport: Sections responsive, spacing good
      - Carousels show 2-3 items (breakpoint correct)
      - Genre filters wrap properly
      - Touch interactions work if available
    - **Mobile (375px, 425px)**:
      - iPhone/Android viewport: Sections stack vertically
      - No horizontal scrolling
      - Hero section readable
      - Carousels show 1 item, swipeable
      - Form inputs easy to tap (≥48px)
      - Newsletter form works on mobile
    - **Cross-browser**: Test on Safari iOS, Chrome Android
  - Document any issues found and pass to task 16

  **Must NOT do**:
  - Don't fix bugs in this task (pass to task 16)
  - Don't make code changes

  **Recommended Agent Profile**:
  - **Category**: `unspecified-high` (+ `playwright` skill) — Manual QA and browser testing
    - Reason: Comprehensive testing across multiple viewports and browsers requires systematic verification

  **Parallelization**:
  - **Can Run In Parallel**: NO (depends on all previous tasks)
  - **Parallel Group**: Wave 4 start
  - **Blocks**: Task 16, F1-F4
  - **Blocked By**: Tasks 12, 13, 14

  **References**:
  - All tasks 5-14 deliverables (homepage, carousels, forms, styles)

  **Acceptance Criteria**:
  - [ ] Manual testing completed on 3+ browsers (desktop)
  - [ ] Tested at 375px, 768px, 1024px, 1440px viewports
  - [ ] Zero console JavaScript errors
  - [ ] All interactive elements work (carousels, forms, links)
  - [ ] Responsive layout correct at each breakpoint
  - [ ] QA report generated with findings

  **QA Scenarios** (Comprehensive Manual Testing):

  \`\`\`
  Scenario: Full homepage renders without errors (Chrome desktop)
    Tool: Playwright
    Steps:
      1. Navigate to http://localhost:8000
      2. Wait for page load (all images loaded)
      3. Check console for errors: const errors = window.__errors__ || []; console.error('Errors:', errors);
      4. Verify hero visible: document.querySelector('.hero') ? ✅ : ❌
      5. Verify carousels: document.querySelectorAll('.swiper').length >= 3 ? ✅ : ❌
      6. Verify filters: document.querySelector('.genre-filters') ? ✅ : ❌
      7. Verify newsletter: document.querySelector('.newsletter-banner') ? ✅ : ❌
      8. Verify news: document.querySelector('.news-preview') ? ✅ : ❌
    Expected Result: All sections present, no console errors
    Evidence: .sisyphus/evidence/task-15-qa-desktop-chrome.png

  Scenario: Mobile responsiveness at 375px
    Tool: Playwright
    Steps:
      1. Set viewport to 375px width
      2. Navigate to homepage
      3. Check horizontal scroll: document.documentElement.scrollWidth <= 375 ? ✅ : ❌ (horizontal scroll detected)
      4. Verify hero mobile: hero element visible and readable
      5. Test carousel swipe: Click and drag to simulate swipe, carousel should advance
      6. Test touch targets: All buttons/links ≥48px tall
    Expected Result: No horizontal scroll, all elements readable and interactive on mobile
    Evidence: .sisyphus/evidence/task-15-qa-mobile-375px.png

  Scenario: Carousel functionality (all 3 working)
    Tool: Playwright
    Steps:
      1. For each carousel (.swiper):
         - Click prev button → verify slide changes
         - Click next button → verify slide advances
         - Wait 5 seconds → verify auto-play advances slide
         - Verify pagination dots clickable
      2. On mobile: Swipe left → carousel advances
    Expected Result: All carousel controls functional (prev, next, dots, auto-play, swipe)
    Evidence: .sisyphus/evidence/task-15-qa-carousels.txt
  \`\`\`

  **Commit**: NO (data collection only)

---

- [ ] **16. Fix Edge Cases & Polish**

  **What to do**:
  - Address any issues found in task 15 manual QA:
    - Fix console errors (JavaScript errors, warnings)
    - Fix responsive layout issues (horizontal scroll, overflow, text cutoff)
    - Fix carousel issues (slides not sizing correctly, navigation broken)
    - Add fallbacks: If Swiper CDN fails, show static grid instead of blank
    - Handle empty states: If no movies/news, show "No content available" message
    - Clean up dead code or unused CSS
    - Optimize images if necessary (lazy loading, alt text)
  - Re-test all fixed issues

  **Must NOT do**:
  - Don't add new features
  - Don't refactor large sections of code
  - Don't optimize database queries

  **Recommended Agent Profile**:
  - **Category**: `deep` — Bug fixes, edge case handling, polish
    - Reason: Requires debugging, understanding of various error scenarios, systematic problem-solving

  **Parallelization**:
  - **Can Run In Parallel**: NO (depends on QA findings from task 15)
  - **Parallel Group**: Wave 4
  - **Blocks**: Task 17, F1-F4
  - **Blocked By**: Task 15

  **References**:
  - QA report from task 15 (issues found)
  - All previous tasks' code

  **Acceptance Criteria**:
  - [ ] All high-priority bugs fixed (console errors, broken carousels, layout issues)
  - [ ] Fallback for missing Swiper implemented
  - [ ] Empty state messages added
  - [ ] Re-tested: no new errors introduced

  **QA Scenarios**:

  \`\`\`
  Scenario: CDN fallback works if Swiper unavailable
    Tool: Playwright
    Steps:
      1. Simulate CDN failure: In DevTools, block swiper CDN domain
      2. Navigate to homepage
      3. Check if fallback renders: If no Swiper, should show static carousel (grid of all items) or message
      4. Verify no error thrown
    Expected Result: Graceful fallback, page still usable without Swiper
    Evidence: .sisyphus/evidence/task-16-cdn-fallback.png

  Scenario: Empty state handling (no movies)
    Tool: Bash (modify DB temporarily)
    Steps:
      1. Create test scenario with no recommended movies (or mock in controller)
      2. Navigate to homepage
      3. Verify carousel shows "No recommendations available" or similar message
      4. Verify page doesn't crash
    Expected Result: Graceful empty state, no errors
    Evidence: .sisyphus/evidence/task-16-empty-state.png

  Scenario: No console errors after fixes
    Tool: Playwright
    Steps:
      1. Navigate to homepage
      2. Open DevTools console
      3. Check for errors: console.getEventListeners(window).error.length === 0 ? ✅ : ❌
      4. Verify: No red errors in console
    Expected Result: Console clean, no JavaScript errors
    Evidence: .sisyphus/evidence/task-16-console-clean.png
  \`\`\`

  **Commit**: YES
  - Message: `fix(homepage): address edge cases and improve reliability`
  - Files: Modified files from tasks 5-14 (e.g., home/index.php, home.css, home.js)
  - Pre-commit: Manual verify no console errors

---

- [ ] **17. Git Cleanup & Final Commit**

  **What to do**:
  - Verify all changes committed:
    - If not already committed (tasks 1-4 and 14): commit all files
    - Review commit history: `git log --oneline` should show clear commit messages
    - Ensure feature branch merged cleanly (if using branches)
  - Final cleanup:
    - Remove any debug code, console.log, comments
    - Verify no uncommitted changes: `git status`
    - Tag final version (optional): `git tag -a v1.0-homepage-rewrite`

  **Must NOT do**:
  - Don't force push or rewrite history
  - Don't delete any branches without explicit instruction
  - Don't merge into main without confirmation

  **Recommended Agent Profile**:
  - **Category**: `quick` (with `git-master` skill) — Git operations and commit management
    - Reason: Commit hygiene, branch management, version tagging; requires git-master skill for proper git workflows

  **Parallelization**:
  - **Can Run In Parallel**: NO (depends on task 16)
  - **Parallel Group**: Wave 4
  - **Blocks**: F1-F4
  - **Blocked By**: Task 16

  **References**:
  - Git history from session

  **Acceptance Criteria**:
  - [ ] All changes committed (git status clean)
  - [ ] Commit messages clear and following convention
  - [ ] No debug code or console.log left
  - [ ] Feature branch ready for merge (or already merged if main)

  **QA Scenarios**:

  \`\`\`
  Scenario: Git status clean
    Tool: Bash (git)
    Steps:
      1. Run: git status
      2. Verify: "nothing to commit, working tree clean"
    Expected Result: All changes committed
    Evidence: .sisyphus/evidence/task-17-git-clean.txt

  Scenario: Commit history follows convention
    Tool: Bash (git)
    Steps:
      1. Run: git log --oneline | head -10
      2. Verify: Each commit follows pattern "type(scope): description"
      3. Examples: feat(homepage), fix(homepage), chore(components)
    Expected Result: Clear, conventional commit history
    Evidence: .sisyphus/evidence/task-17-commit-history.txt
  \`\`\`

  **Commit**: N/A (this task creates final commit)

---

## Final Verification Wave (MANDATORY)

---

## Commit Strategy

### Commit Structure (Summary)

```
feat(database): create settings table and featured movie seed
  - Create settings table for homepage configuration
  - Add featured_movie_id setting for admin control

feat(controller): add homepage data fetching logic
  - Fetch featured movie, recommendations, genres, coming soon, news
  - Add queries for carousel and filter data

feat(layout): add Swiper.js CDN links
  - Add Swiper CSS and JS CDN to main layout

chore(components): create components directory structure
  - Initialize view components directory for reusable sections

feat(homepage): complete homepage sections, styling, and interactivity
  - Build hero, carousel, filter, newsletter, news sections
  - Add responsive CSS for 375px, 768px, 1024px breakpoints
  - Initialize Swiper carousels with auto-play and navigation
  - Add email validation for newsletter form

fix(homepage): address edge cases and improve reliability
  - Fix console errors and responsive layout issues
  - Add CDN fallback and empty state handling
  - Clean up debug code
```

---

## Success Criteria (Definition of Done)

### Verification Commands

```bash
# 1. PHP Syntax Check
php -l app/Controllers/HomeController.php && php -l app/Views/home/index.php && echo "✅ No syntax errors"

# 2. Database Connectivity
php -r "
  \$conn = new mysqli('localhost', 'root', '', 'cgv_cinema');
  \$result = \$conn->query('SELECT COUNT(*) FROM settings');
  echo \$result->fetch_row()[0] > 0 ? '✅ Settings OK' : '⚠️ Settings empty';
"

# 3. Homepage Loads
curl -s http://localhost:8000 | grep -c "<section" && echo "✅ Sections present"

# 4. No PHP Errors
curl -s http://localhost:8000 | grep -qi "fatal\|error" && echo "⚠️ Errors" || echo "✅ No PHP errors"

# 5. Git Clean
git status | grep -q "nothing to commit" && echo "✅ Git clean" || echo "⚠️ Uncommitted changes"
```

### Final Checklist

- [ ] ✅ Hero section with featured movie (admin-controlled via settings table)
- [ ] ✅ 3 carousels functional (recommendations, ads, coming soon)
- [ ] ✅ Genre filters clickable and link correctly
- [ ] ✅ Newsletter form validates email input
- [ ] ✅ News preview section shows 4 items
- [ ] ✅ Responsive at 375px, 768px, 1024px, 1440px
- [ ] ✅ All CSS uses existing --cgv-* variables
- [ ] ✅ No console JavaScript errors
- [ ] ✅ No PHP warnings or errors
- [ ] ✅ Tested in Chrome, Firefox, Edge, Safari iOS, Chrome Android
- [ ] ✅ Database queries optimized (basic SELECT + WHERE + LIMIT)
- [ ] ✅ No schema modifications (existing tables only)
- [ ] ✅ No new Model classes created
- [ ] ✅ No API endpoints added
- [ ] ✅ Git history clean with conventional commits
- [ ] ✅ All QA scenarios passed with evidence captured

## Final Verification Wave (MANDATORY)

- [ ] **F1. Plan Compliance Audit** — `deep`
  Read plan end-to-end and verify each deliverable exists:
  - Featured movie hero renders (read hero HTML, check settings table query)
  - 3 carousels render with Swiper (grep for Swiper init, check carousel markup)
  - Genre filters link correctly (test `/movies/current?genre=ACTION` URLs in HTML)
  - Newsletter form has validation (grep for email validation JS)
  - News section displays (check news query in controller, verify limit 4)
  - Responsive CSS present (grep media queries at 768px, check viewport meta tag)
  - All QA scenarios passed (evidence files exist in .sisyphus/evidence/)
  Output: `Deliverables [7/7] ✅ | QA Scenarios [15+/15+] ✅ | VERDICT: APPROVE/REJECT`

- [ ] **F2. Code Quality Review** — `unspecified-high`
  Run `php -l app/Controllers/HomeController.php` + `php -l app/Views/home/index.php` (syntax check).
  Review code for:
  - No `console.log` in production JS (should be removed)
  - No `@ts-ignore` / `as any` equivalents
  - No commented-out code blocks
  - CSS: No excessive `!important` usage (only where overriding Bootstrap if absolutely necessary)
  - No empty catch blocks in JS error handling
  Output: `Syntax [PASS/FAIL] | Style [PASS/FAIL] | Warnings [N] | VERDICT`

- [ ] **F3. Real Manual QA** — `unspecified-high` (+ `playwright` skill)
  Execute EVERY QA scenario from tasks 5-14:
  - Test hero section on desktop + mobile
  - Navigate all 3 carousels (prev/next buttons, auto-play, swipe on mobile)
  - Click 3-4 genre filters, verify URL change
  - Test newsletter form (empty, invalid, valid email)
  - View news section on desktop + mobile
  - Test responsiveness at 375px, 768px, 1024px, 1440px viewports
  - Check Swiper console: `window.Swiper !== undefined` ✅
  Output: `Desktop ✅ | Mobile ✅ | Responsiveness ✅ | Interactions ✅ | VERDICT`

- [ ] **F4. Scope Fidelity Check** — `deep`
  For each task 5-14: read "What to do" acceptance criteria, compare against actual code diff.
  - Task 5 (hero): Hero section exists with correct selectors and data binding? ✅
  - Task 6-9, 11 (sections): All sections render with correct data from controller? ✅
  - Task 12 (CSS): All responsive breakpoints implemented? ✅
  - Task 13 (Swiper): All 3 carousels initialized with correct options? ✅
  - Task 14 (JS): Form validation working as spec? ✅
  Detect scope creep: Any extra features not in original plan? Any "nice-to-haves" added?
  Output: `Tasks [17/17 compliant] | Creep [CLEAN/N issues] | VERDICT`

---

## Commit Strategy

### Commit Structure (Git)

```
feat(homepage): initial setup and data layer
- Create settings table (featured_movie_id)
- Add HomeController data fetching methods
- Add Swiper.js CDN link to main layout
- Create view components directory structure

feat(homepage): hero and carousels HTML structure
- Add featured movie hero section
- Add movie recommendation carousel markup
- Add ads banner carousel markup
- Add coming soon carousel markup
- Add genre filter bar
- Add newsletter signup banner
- Add news preview section

feat(homepage): responsive styling and layout
- Add responsive CSS for homepage sections
- Implement breakpoints (768px, 1024px)
- Style carousels, hero, sections with Bootstrap grid
- Use CSS variables for branding

feat(homepage): carousel and form functionality
- Initialize Swiper.js for 3 carousels
- Add carousel auto-play and navigation
- Add newsletter email validation
- Add form submission handlers
- Add error/success messaging

fix(homepage): edge cases and polish
- Handle empty states (no movies, no news)
- Add CDN fallback for Swiper
- Fix responsive layout on mobile
- Clean up console errors
- Test across browsers

Commit Types: feat (new feature), fix (bug fix), style (CSS), refactor (code structure)
Pre-commit: Manual verification of carousel functionality + responsive testing
```

---

## Success Criteria (Definition of Done)

### Verification Commands (Run These to Confirm Success)

```bash
# 1. PHP Syntax Check
php -l app/Controllers/HomeController.php && php -l app/Views/home/index.php && echo "✅ No syntax errors"

# 2. Database Connectivity + Settings Table
php -r "
  \$conn = new mysqli('localhost', 'root', '', 'cgv_cinema');
  \$result = \$conn->query('SELECT COUNT(*) as cnt FROM information_schema.TABLES WHERE TABLE_NAME=\"settings\"');
  \$row = \$result->fetch_assoc();
  echo \$row['cnt'] > 0 ? '✅ Settings table exists' : '⚠️ Settings table missing';
"

# 3. Homepage Loads Without Errors (Playwright will do detailed UI test)
curl -s http://localhost:8000 | grep -c "<section" && echo "✅ Sections present"

# 4. Swiper Library Loaded (check for CDN script tag in response)
curl -s http://localhost:8000 | grep -q "swiper" && echo "✅ Swiper CDN linked"

# 5. No Critical PHP Errors in Response
curl -s http://localhost:8000 | grep -qi "error\|exception\|fatal" && echo "⚠️ PHP errors found" || echo "✅ No PHP errors"
```

### Final Checklist

- [ ] ✅ All 7 main sections visible and rendering on homepage
- [ ] ✅ Swiper.js carousels initialized (3 carousels functional)
- [ ] ✅ Genre filters clickable and navigate to correct URLs
- [ ] ✅ Newsletter form validates email input
- [ ] ✅ Page responsive at 375px, 768px, 1024px, 1440px
- [ ] ✅ No console JavaScript errors
- [ ] ✅ No PHP warnings/notices in server logs
- [ ] ✅ Tested in Chrome, Firefox, Edge (desktop) and Safari iOS, Chrome Android (mobile)
- [ ] ✅ Featured movie displays from settings table (or falls back to first active movie)
- [ ] ✅ News section shows 4 items max with "View All" link
- [ ] ✅ All CSS uses existing --cgv-* variables (no hardcoded colors)
- [ ] ✅ Bootstrap 5 grid classes used for responsive layout (no custom breakpoints)
- [ ] ✅ No database schema modifications (existing tables only)
- [ ] ✅ No new Model classes created (queries in Controller)
- [ ] ✅ No API endpoints added (traditional PHP routing only)