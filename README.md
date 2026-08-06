# CGV Cinema Booking System

A full-featured cinema ticket booking web application built with a custom PHP MVC framework. This project is developed as part of the Web Programming course (HK2 2025-2026).

## Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Prerequisites](#prerequisites)
- [Installation](#installation)
- [Usage](#usage)
- [Project Structure](#project-structure)
- [Database Setup](#database-setup)
- [Test Accounts](#test-accounts)
- [Routes](#routes)
- [CI/CD](#cicd)
- [Contributing](#contributing)
- [License](#license)

## Features

### User Features
- **Movie Browsing**: Browse now showing and coming soon movies
- **Movie Details**: View detailed movie information (cast, director, trailer, ratings)
- **Showtimes**: View available showtimes by cinema and date
- **Seat Selection**: Interactive seat selection with real-time availability
- **Combo Deals**: Add food & beverage combos to booking
- **Online Payment**: Complete booking with integrated payment flow
- **User Profile**: Manage profile, view booking history, change password
- **News & Promotions**: Browse cinema news and promotional offers
- **FAQ**: Accordion-style FAQ page with live search
- **Contact Page**: Contact form with email notifications
- **Responsive Design**: Mobile-friendly interface

### Admin Features
- **Dashboard**: Statistics and overview of bookings, users, revenue
- **Movie Management**: CRUD operations for movies with image upload
- **Showtime Management**: Schedule and manage movie showtimes
- **Order Management**: View and manage customer bookings
- **User Management**: Manage user accounts, reset passwords, ban/unban
- **Combo Management**: Manage food & beverage combo offers
- **News Management**: Publish and manage news articles
- **FAQ Management**: Manage FAQ categories and items with bulk actions
- **Static Pages**: Manage About, Terms, Privacy pages with TinyMCE editor
- **Comment Moderation**: Approve/reject user comments on news
- **Theater Management**: Manage cinema locations and room configurations

### Technical Features
- **Custom MVC Framework**: Built from scratch without external PHP frameworks
- **Security**: CSRF protection, XSS prevention, SQL injection prevention via prepared statements
- **File Upload**: Secure image upload with validation
- **Email Integration**: SMTP support for notifications and verification
- **Session Management**: Secure authentication system
- **Search & Filter**: Advanced search across movies, news, and admin tables
- **Pagination**: Efficient pagination for large datasets
- **GitHub Actions**: Automated CI/CD with linting, security scans, and live demo

## Tech Stack

- **Backend**: PHP 8.x (no frameworks)
- **Database**: MySQL 8.x
- **Frontend**: HTML5, CSS3, JavaScript
- **CSS Framework**: Bootstrap 5.3.3
- **Icons**: Font Awesome 6
- **Animation**: AOS (Animate On Scroll), Swiper.js
- **Editor**: TinyMCE (for admin content management)
- **Server**: Apache (with mod_rewrite) or PHP built-in server

## Prerequisites

Before you begin, ensure you have the following installed:

- **PHP 8.x** or higher
- **MySQL 8.x** or higher
- **Web Server**: Apache with `mod_rewrite` enabled, or use PHP built-in server
- **Git** (for cloning)

### PHP Extensions Required

```bash
# Check your PHP extensions
php -m

# Required extensions:
- PDO
- PDO_MySQL
- mbstring
- curl
- json
- fileinfo
- session
```

## Installation

### 1. Clone the Repository

```bash
git clone https://github.com/fluoxetines73/BTL-LTW-HK252.git
cd BTL-LTW-HK252
```

### 2. Create Database

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE IF NOT EXISTS cgv_booking 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;

EXIT;
```

### 3. Import Database Schema

```bash
# Import schema (creates all tables)
mysql -u root -p cgv_booking < database/01-schema.sql

# Import seed data (test users, sample movies, etc.)
mysql -u root -p cgv_booking < database/02-seed.sql

# Optional: Import extended data
mysql -u root -p cgv_booking < database/03-seed-movies.sql
mysql -u root -p cgv_booking < database/04-seed-showtimes-combos.sql
mysql -u root -p cgv_booking < database/05-seed-tmdb.sql
mysql -u root -p cgv_booking < database/06-setup-rooms-seats.sql
```

**On Windows PowerShell:**
```powershell
Get-ChildItem database\*.sql | Sort-Object Name | ForEach-Object { mysql -u root -p cgv_booking < $_.FullName }
```

### 4. Configure Environment

```bash
# Copy example environment file
cp .env.example .env

# Edit .env with your database and SMTP credentials
```

Edit `.env` file:

```env
# Database Configuration
DB_HOST=localhost
DB_NAME=cgv_booking
DB_USER=root
DB_PASS=your_mysql_password

# SMTP Configuration (for email features)
SMTP_HOST=smtp.gmail.com
SMTP_PORT=587
SMTP_USERNAME=your_email@gmail.com
SMTP_PASSWORD=your_app_password
SMTP_ENCRYPTION=tls
SMTP_FROM_EMAIL=your_email@gmail.com
SMTP_FROM_NAME=CGV Booking
SMTP_TIMEOUT=20
```

### 5. Set Up Upload Directories

Ensure these directories are writable by the web server:

```bash
# Create upload directories if they don't exist
mkdir -p public/uploads/avatars
mkdir -p public/uploads/movies
mkdir -p public/uploads/combos

# Set permissions (Linux/Mac)
chmod -R 755 public/uploads
```

### 6. Configure Web Server

#### Option A: Apache

Ensure `.htaccess` file exists in the project root:

```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]
```

Enable `mod_rewrite`:
```bash
# Ubuntu/Debian
sudo a2enmod rewrite
sudo systemctl restart apache2

# Update Apache config to AllowOverride All for the project directory
```

#### Option B: PHP Built-in Server (Development Only)

```bash
php -S localhost:8000
```

The application will be available at `http://localhost:8000`

### 7. Verify Installation

Open your browser and navigate to:
- **Homepage**: `http://localhost:8000`
- **Admin Login**: `http://localhost:8000/auth/login`

## Usage

### User Guide

#### Browsing Movies
1. Visit the homepage to see featured and current movies
2. Click "Xem chi tiết" to view movie details
3. Navigate to "Phim đang chiếu" for all current movies
4. Navigate to "Phim sắp chiếu" for upcoming releases

#### Booking Tickets
1. Select a movie and click "Đặt vé ngay"
2. Choose showtime and cinema
3. Select seats from the interactive seat map
4. Add food & beverage combos (optional)
5. Proceed to checkout
6. Complete payment
7. Receive confirmation email with booking details

#### Managing Profile
1. Login to your account
2. Click on your name in the header
3. Select "Thông tin cá nhân" to view profile
4. Select "Chỉnh sửa" to update information
5. Select "Đổi mật khẩu" to change password
6. View booking history in "Lịch sử đặt vé"

### Admin Guide

#### Accessing Admin Panel
1. Login with admin credentials
2. You'll be redirected to the admin dashboard
3. Use the sidebar to navigate between management sections

#### Managing Movies
1. Go to "Quản lý phim"
2. Click "Thêm phim mới" to add a movie
3. Fill in movie details, upload poster and banner
4. Save to create the movie
5. Use search and filters to find existing movies
6. Click edit/delete icons to manage movies

#### Managing Showtimes
1. Go to "Quản lý suất chiếu"
2. Click "Thêm suất chiếu"
3. Select movie, cinema, room, date, and time
4. Set ticket prices
5. Save to create showtime

#### Managing Orders
1. Go to "Quản lý đơn hàng"
2. View all bookings with status (pending, confirmed, cancelled)
3. Click to view order details
4. Update order status as needed

#### Managing Content (CMS)
1. **FAQ**: Go to "Quản lý FAQ" to manage questions and answers
2. **Static Pages**: Go to "Quản lý trang tĩnh" for Terms, Privacy, etc.
3. **About Page**: Go to "Quản lý trang giới thiệu" to edit company info
4. **News**: Go to "Quản lý tin tức" to publish articles

#### Managing Comments
1. Go to "Quản lý bình luận"
2. Review pending comments
3. Approve or delete comments
4. View reported comments and take action

## Project Structure

```
BTL-LTW-HK252/
├── app/
│   ├── Controllers/         # All controllers (user & admin)
│   │   ├── HomeController.php
│   │   ├── MoviesController.php
│   │   ├── AdminMovieController.php
│   │   └── ...
│   ├── Models/              # Database models
│   │   ├── Model.php        # Base model class
│   │   ├── User.php
│   │   ├── Movie.php
│   │   └── ...
│   └── Views/               # PHP templates
│       ├── layouts/         # Main layout files
│       │   ├── main.php     # User layout
│       │   └── admin.php    # Admin layout
│       ├── admin/           # Admin panel views
│       ├── auth/            # Login/register pages
│       ├── movies/          # Movie-related views
│       ├── news/            # News-related views
│       └── ...
├── core/                    # Framework core files
│   ├── Router.php          # URL routing
│   ├── Database.php        # PDO connection
│   └── Controller.php      # Base controller
├── configs/                 # Configuration files
│   └── database.php        # Database constants
├── database/                # SQL files
│   ├── 01-schema.sql       # Table structure
│   ├── 02-seed.sql         # Sample data
│   └── ...
├── public/                  # Public assets
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript files
│   ├── uploads/            # User uploads
│   │   ├── avatars/
│   │   ├── movies/
│   │   └── combos/
│   └── images/             # Static images
├── .github/
│   └── workflows/          # CI/CD configurations
├── .env                    # Environment variables (create from .env.example)
├── .env.example            # Environment template
├── .htaccess              # Apache rewrite rules
├── index.php              # Application entry point
└── README.md              # This file
```

## Database Setup

### Quick Setup

```bash
# 1. Create database
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS cgv_booking CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 2. Import schema + seed (minimum required)
mysql -u root -p cgv_booking < database/01-schema.sql
mysql -u root -p cgv_booking < database/02-seed.sql
```

### Full Setup with Extended Data

| File | Description | Required |
|------|-------------|----------|
| `01-schema.sql` | Creates 31+ tables (users, movies, bookings, etc.) | ✅ Yes |
| `02-seed.sql` | Sample data: 4 users, 6 movies, 2 cinemas, FAQ, etc. | ✅ Yes |
| `03-seed-movies.sql` | Additional 25 movies | ❌ Optional |
| `04-seed-showtimes-combos.sql` | 25 showtimes + 25 combo deals | ❌ Optional |
| `05-seed-tmdb.sql` | 42 movies from TMDB API | ❌ Optional |
| `06-setup-rooms-seats.sql` | 3 cinema rooms with 330 seats total | ❌ Optional |

See `database/README_CSDL.md` for detailed instructions.

## Test Accounts

After importing `02-seed.sql`:

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@cgv.vn | password |
| Member | test@example.com | password |
| Member | test2@example.com | password |
| Member | member@test.com | password |

## Routes

URL Pattern: `/{controller}/{method}/{param1}/{param2}`

### Public Routes

| Route | Description |
|-------|-------------|
| `/` | Homepage |
| `/movies` | Movie list (now showing) |
| `/movies/coming` | Coming soon movies |
| `/movie/detail/{id}` | Movie detail page |
| `/theaters` | Cinema locations |
| `/news` | News & promotions |
| `/news/detail/{id}` | News article |
| `/pages/about` | About page |
| `/pages/faq` | FAQ page |
| `/pages/contact` | Contact page |
| `/auth/login` | Login page |
| `/auth/register` | Registration page |
| `/profile` | User profile (requires login) |
| `/cart` | Shopping cart (requires login) |
| `/checkout` | Checkout (requires login) |

### Admin Routes

| Route | Description |
|-------|-------------|
| `/admin` | Admin dashboard |
| `/admin/movies` | Movie management |
| `/admin/showtimes` | Showtime management |
| `/admin/orders` | Order management |
| `/admin/users` | User management |
| `/admin/combo` | Combo management |
| `/admin/news` | News management |
| `/admin/faq` | FAQ management |
| `/admin/pages` | Static page management |
| `/admin/about` | About page CMS |
| `/admin/comments` | Comment moderation |

## CI/CD

This project uses GitHub Actions for continuous integration:

### Workflows

1. **PHP Lint** (`.github/workflows/php-lint.yml`)
   - Validates PHP syntax
   - Detects debug code (var_dump, die, print_r)
   - Checks MVC structure

2. **Security Scan** (`.github/workflows/security-scan.yml`)
   - Scans for SQL injection patterns
   - Detects XSS vulnerabilities
   - Checks for hardcoded secrets

3. **Live Demo** (`.github/workflows/live-demo.yml`)
   - Sets up full environment
   - Creates MySQL database
   - Imports schema and seed data
   - Verifies routes work

4. **Build Report** (`.github/workflows/build-report.yml`)
   - Reports file counts
   - Validates MVC structure
   - Summarizes project health

5. **PR Checklist** (`.github/workflows/pr-checklist.yml`)
   - Auto-comments on PRs
   - Provides pre-merge checklist

### Running Checks Locally

```bash
# Check PHP syntax
find . -name "*.php" -exec php -l {} \;

# Check for debug code
grep -r "var_dump\|print_r\|die(" app/ core/ --include="*.php"
```

## Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/your-feature`
3. Commit your changes: `git commit -m "feat: add new feature"`
4. Push to the branch: `git push origin feature/your-feature`
5. Open a Pull Request

### Commit Message Convention

```
feat: add new feature
fix: fix a bug
docs: update documentation
style: formatting changes
refactor: code restructuring
test: add tests
chore: maintenance tasks
```

See `docs/GIT_WORKFLOW.md` for detailed workflow instructions.

## Documentation

- `docs/assignment.md` - Assignment requirements
- `docs/GIT_WORKFLOW.md` - Git workflow and conventions
- `docs/GITHUB_ACTIONS.md` - CI/CD documentation
- `docs/UI_UX_GUIDELINES.md` - UI/UX standards and design system
- `database/README_CSDL.md` - Database setup guide
- `admin_guide.md` - Detailed admin setup (Vietnamese)

## License

This project is developed for educational purposes as part of the Web Programming course at Ho Chi Minh City University of Technology (HCMUT).

## Team

- **Member #1**: Homepage, Contact Page, User Management, Settings
- **Member #2**: About Page, FAQ, Pages CMS, Admin Theme, Search/Filter
- **Member #3**: Product/Combo Management, Cart, Checkout, Orders
- **Member #4**: News, Comments, Movie Management, Showtimes

## Acknowledgments

- Bootstrap 5 for the CSS framework
- Font Awesome for icons
- AOS library for scroll animations
- Swiper.js for carousels
- TinyMCE for rich text editing
- TMDB for movie data
