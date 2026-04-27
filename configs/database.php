<?php
// Load database credentials from environment variables for security.
// Copy .env.example to .env and configure your actual values there.
// NEVER commit passwords or sensitive credentials to version control.

define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'cgv_booking');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: ''); // IMPORTANT: Set via .env or environment variable
