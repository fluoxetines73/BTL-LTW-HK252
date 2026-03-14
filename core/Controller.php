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
            die("View không tồn tại: $view");
        }
    }

    // Redirect
    protected function redirect(string $url): void {
        $target = rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
        header("Location: " . $target);
        exit();
    }
}