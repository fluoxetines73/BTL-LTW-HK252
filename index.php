<?php
define('ROOT', dirname(__FILE__));
define('APPROOT', ROOT . '/app');

// Load environment variables from .env file
$envFile = ROOT . '/.env';
if (file_exists($envFile)) {
    $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Skip comments
        if (strpos(trim($line), '#') === 0) continue;
        
        // Parse KEY=VALUE
        if (strpos($line, '=') !== false) {
            list($key, $value) = explode('=', $line, 2);
            $key = trim($key);
            $value = trim($value);
            // Remove quotes if present
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