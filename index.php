<?php
// Check PHP version compatibility
if (PHP_VERSION_ID < 70400) {
    die("Error: This application requires PHP 7.4.0 or higher. Current version: " . PHP_VERSION);
}

// Check required extensions
$required_extensions = ['pdo', 'pdo_mysql'];
$missing_extensions = [];
foreach ($required_extensions as $ext) {
    if (!extension_loaded($ext)) {
        $missing_extensions[] = $ext;
    }
}
if (!empty($missing_extensions)) {
    die("Error: Missing required PHP extensions: " . implode(', ', $missing_extensions));
}

define('ROOT', dirname(__FILE__));
define('APPROOT', ROOT . '/app');

$envFile = ROOT . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            $value = trim($value, '\'"');
            if (!empty($key)) {
                putenv("{$key}={$value}");
            }
        }
    }
}

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$scriptDir = rtrim($scriptDir, '/');
define('BASE_URL', $scriptDir === '' ? '/' : $scriptDir . '/');

require_once ROOT . '/core/Router.php';

$router = new Router();
$router->dispatch();