<?php
class Router {
    private string $controller = 'HomeController';
    private string $method     = 'index';
    private array  $params     = [];

    public function dispatch(): void {
        $url = $this->parseUrl();

        // Xác định controller
        if (!empty($url[0])) {
            $controllerName = ucfirst(strtolower($url[0])) . 'Controller';
            $file = APPROOT . '/Controllers/' . $controllerName . '.php';
            if (file_exists($file)) {
                $this->controller = $controllerName;
                unset($url[0]);
            }
        }

        $controllerFile = APPROOT . '/Controllers/' . $this->controller . '.php';
        if (!file_exists($controllerFile)) {
            http_response_code(500);
            die('Controller mặc định không tồn tại.');
        }

        require_once $controllerFile;
        $controller = new $this->controller();

        // Xác định method
        if (!empty($url[1])) {
            if (method_exists($controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // Phần còn lại là params
        $this->params = array_values($url ?? []);

        if (!method_exists($controller, $this->method)) {
            if (method_exists($controller, 'notFound')) {
                http_response_code(404);
                $controller->notFound();
                return;
            }

            http_response_code(404);
            die('Method không tồn tại.');
        }

        call_user_func_array([$controller, $this->method], $this->params);
    }

    private function parseUrl(): array {
        if (isset($_GET['url'])) {
            $raw = filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL);
            $parts = explode('/', $raw);
            return array_values(array_filter($parts, static fn($part) => $part !== ''));
        }

        // Fallback for PHP built-in server (no .htaccess rewrite)
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $path = parse_url($uri, PHP_URL_PATH) ?: '/';

        $basePath = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/'));
        $basePath = rtrim($basePath, '/');
        if ($basePath !== '' && str_starts_with($path, $basePath)) {
            $path = substr($path, strlen($basePath));
        }

        $path = trim($path, '/');
        if ($path === '' || $path === 'index.php') {
            return [];
        }

        $raw = filter_var($path, FILTER_SANITIZE_URL);
        $parts = explode('/', $raw);
        return array_values(array_filter($parts, static fn($part) => $part !== ''));

        return [];
    }
}