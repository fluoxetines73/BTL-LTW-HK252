<?php

// SMTP config for OTP email sending.
// You can replace these placeholders directly or set environment variables.

define('SMTP_HOST', getenv('SMTP_HOST') !== false ? getenv('SMTP_HOST') : '');
define('SMTP_PORT', getenv('SMTP_PORT') !== false ? (int)getenv('SMTP_PORT') : 587);
define('SMTP_USERNAME', getenv('SMTP_USERNAME') !== false ? getenv('SMTP_USERNAME') : '');
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') !== false ? getenv('SMTP_PASSWORD') : '');
define('SMTP_ENCRYPTION', getenv('SMTP_ENCRYPTION') !== false ? getenv('SMTP_ENCRYPTION') : 'tls');
define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') !== false ? getenv('SMTP_FROM_EMAIL') : '');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') !== false ? getenv('SMTP_FROM_NAME') : 'CGV Booking');
define('SMTP_TIMEOUT', getenv('SMTP_TIMEOUT') !== false ? (int)getenv('SMTP_TIMEOUT') : 20);