<?php
define('ROOT', dirname(__FILE__));
define('APPROOT', ROOT . '/app');

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$scriptDir = rtrim($scriptDir, '/');
define('BASE_URL', $scriptDir === '' ? '/' : $scriptDir . '/');

require_once ROOT . '/core/Router.php';

$router = new Router();
$router->dispatch();