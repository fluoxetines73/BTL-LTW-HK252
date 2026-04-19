<?php
define('ROOT', dirname(__FILE__));
define('APPROOT', ROOT . '/app');

if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

// Tự động hết phiên sau 10 phút không hoạt động.
$sessionTimeoutSeconds = 600;
if (!empty($_SESSION['auth_user'])) {
	$now = time();
	$lastActivity = (int)($_SESSION['last_activity'] ?? 0);

	if ($lastActivity > 0 && ($now - $lastActivity) > $sessionTimeoutSeconds) {
		unset($_SESSION['auth_user'], $_SESSION['last_activity']);
		$_SESSION['auth_flash'] = [
			'type' => 'error',
			'message' => 'Phiên đăng nhập đã hết hạn sau 10 phút không hoạt động. Vui lòng đăng nhập lại.',
		];
	} else {
		$_SESSION['last_activity'] = $now;
	}
}

$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
$scriptDir = rtrim($scriptDir, '/');
define('BASE_URL', $scriptDir === '' ? '/' : $scriptDir . '/');

require_once ROOT . '/core/Router.php';

$router = new Router();
$router->dispatch();