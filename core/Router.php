<?php
class Router {
    private string $controller = 'HomeController';
    private string $method     = 'index';
    private array  $params     = [];

    public function dispatch(): void {
        $url = $this->parseUrl();

        // --- BẮT ĐẦU PHẦN THÊM MỚI CHO ADMIN ---
        // 1. Xử lý route cho Admin (Ví dụ: /admin/movie/create -> AdminMovieController::create)
        if (!empty($url[0]) && strtolower($url[0]) === 'admin' && !empty($url[1])) {
            // Ghép chuỗi tạo tên Controller, vd: 'movie' -> 'AdminMovieController'
            $adminControllerName = 'Admin' . ucfirst(strtolower($url[1])) . 'Controller';
            $adminFile = APPROOT . '/Controllers/' . $adminControllerName . '.php';
            
            if (file_exists($adminFile)) {
                $this->controller = $adminControllerName;
                unset($url[0]); // Xóa chữ 'admin' khỏi URL
                unset($url[1]); // Xóa chữ 'movie' khỏi URL
                
                // Re-index lại mảng sao cho Method (vd: 'create') nằm đúng ở vị trí $url[1] 
                // để tương thích hoàn toàn với logic cũ của nhóm ở bên dưới
                $newUrl = [];
                $i = 1;
                foreach ($url as $val) {
                    $newUrl[$i] = $val;
                    $i++;
                }
                $url = $newUrl;
            }
        }
        // --- KẾT THÚC PHẦN THÊM MỚI ---

        // 2. Xác định controller (Logic mặc định)
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
            http_response_code(404);
            require_once APPROOT . '/Controllers/HomeController.php';
            $fallbackController = new HomeController();
            $fallbackController->notFound();
            return;
        }

        require_once $controllerFile;
        $controller = new $this->controller();

        // 3. Xác định method
        if (!empty($url[1])) {
            if (method_exists($controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }

        // 4. Phần còn lại là params (tham số)
        $this->params = array_values($url ?? []);

        if (!method_exists($controller, $this->method)) {
            if (method_exists($controller, 'notFound')) {
                http_response_code(404);
                $controller->notFound();
                return;
            }

            http_response_code(404);
            throw new RuntimeException('Method không tồn tại.');
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