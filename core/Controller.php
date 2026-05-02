<?php
class Controller {
    // Load model theo tên
    protected function model(string $model) {
        require_once APPROOT . '/Models/Model.php';
        require_once APPROOT . '/Models/' . $model . '.php';
        return new $model();
    }

    // Render view, truyền data vào
    protected function view(string $view, array $data = []): void {
        // Biến $data thành các biến riêng lẻ ($title, $products, ...)
        extract($data);

        $file = APPROOT . '/Views/' . $view . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            throw new RuntimeException("View không tồn tại: $view");
        }
    }

    // Redirect
    protected function redirect(string $url): void {
        $target = rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
        header("Location: " . $target);
        exit();
    }


    // Kiểm tra xem người dùng đã đăng nhập chưa
    protected function middlewareAuth(): void {
        if (!isset($_SESSION['auth_user'])) {
            $this->redirect('auth/login');
            exit();
        }
    }

    // Kiểm tra xem có phải Admin không
    protected function middlewareAdmin(): void {
        $this->middlewareAuth();
        if ($_SESSION['auth_user']['role'] !== 'admin') {
            // Nếu không phải admin, chuyển về trang chủ [cite: 5]
            $this->redirect('home/index');
            exit();
        }
    }

    /**
     * Render admin view with sidebar navigation
     * @param string $content Content view path
     * @param string $activeSection Active sidebar section
     * @param array $data Additional data
     */
    protected function adminView(string $content, string $activeSection, array $data = []): void {
        $data['content'] = $content;
        $data['activeSection'] = $activeSection;
        $data['title'] = $data['title'] ?? ucfirst($activeSection);
        $this->view('layouts/admin', $data);
    }

}