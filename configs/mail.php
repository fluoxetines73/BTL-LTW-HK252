<?php

// SMTP config for OTP email sending.
// Load from environment variables for security (use .env file or system env vars)
// Never commit credentials to version control.

define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 587);
define('SMTP_USERNAME', getenv('SMTP_USERNAME') ?: '');
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: ''); // IMPORTANT: Set via .env or environment variable
define('SMTP_ENCRYPTION', getenv('SMTP_ENCRYPTION') ?: 'tls');
define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: '');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'CGV Booking');
define('SMTP_TIMEOUT', getenv('SMTP_TIMEOUT') ?: 20);