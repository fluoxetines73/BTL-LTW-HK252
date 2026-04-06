# MVC Skeleton - Team Baseline (Phase 1)

This folder is the shared foundation for all team members.

## Route Convention

- Pattern: `/{controller}/{method}/{param1}/{param2}`
- Default route: `/` -> `HomeController::index`
- Valid examples:
  - `/product` -> `ProductController::index`
  - `/product/detail/2` -> `ProductController::detail(2)`
  - `/news` -> `NewsController::index`
  - `/auth/login` -> `AuthController::login`

## Shared Template Convention

- Main layout: `app/Views/layouts/main.php`
- Header: `app/Views/layouts/header.php`
- Footer: `app/Views/layouts/footer.php`
- Base CSS: `public/css/style.css`
- Base JS: `public/js/app.js`

## Model Convention

- Base model: `app/Models/Model.php`
- Concrete models define `$table` and feature methods.
- Product and News have `search()` sample methods.

## Team Integration Rules

- Keep controller names in `PascalCase + Controller`.
- Keep method names `camelCase`.
- Keep view path matching action purpose.
- Validate inputs on both JS (client) and PHP (server).
- Do not use PHP frameworks.

## Setup Notes

1. Import `database/schema.sql` into MySQL.
2. Update DB credentials in `configs/database.php`.
3. Serve project from Apache (for `.htaccess` rewrite support).
4. Access app from browser using the MVC folder URL.

run website
php -S localhost:8000
