<?php
require_once __DIR__ . '/Database.php';

class Controller {
    protected function model(string $model) {
        require_once APPROOT . '/Models/Model.php';
        require_once APPROOT . '/Models/' . $model . '.php';
        return new $model();
    }

    protected function view(string $view, array $data = []): void {
        extract($data);

        $file = APPROOT . '/Views/' . $view . '.php';
        if (file_exists($file)) {
            require_once $file;
        } else {
            throw new RuntimeException("View không tồn tại: $view");
        }
    }

    protected function redirect(string $url): void {
        $target = rtrim(BASE_URL, '/') . '/' . ltrim($url, '/');
        header("Location: " . $target);
        exit();
    }


    protected function middlewareAuth(): void {
        if (!isset($_SESSION['auth_user'])) {
            $this->redirect('auth/login');
            exit();
        }
    }

    protected function middlewareAdmin(): void {
        $this->middlewareAuth();
        if ($_SESSION['auth_user']['role'] !== 'admin') {
            $this->redirect('home/index');
            exit();
        }
    }

    protected function adminView(string $content, string $activeSection, array $data = []): void {
        $data['content'] = $content;
        $data['activeSection'] = $activeSection;
        $data['title'] = $data['title'] ?? ucfirst($activeSection);
        if (!isset($data['stats'])) {
            $data['stats'] = $this->getAdminStats();
        }
        $this->view('layouts/admin', $data);
    }

    protected function getAdminStats(): array {
        try {
            $db = Database::getInstance()->getPdo();
            return [
                'users' => $this->safeCount($db, "SELECT COUNT(*) FROM users"),
                'movies' => $this->safeCount($db, "SELECT COUNT(*) FROM movies"),
                'showtimes' => $this->safeCount($db, "SELECT COUNT(*) FROM showtimes"),
                'combos' => $this->safeCount($db, "SELECT COUNT(*) FROM combos"),
                'news' => $this->safeCount($db, "SELECT COUNT(*) FROM news"),
            ];
        } catch (Throwable $e) {
            return [
                'users' => 0,
                'movies' => 0,
                'showtimes' => 0,
                'combos' => 0,
                'news' => 0,
            ];
        }
    }

    protected function safeCount(PDO $db, string $sql): int {
        try {
            $stmt = $db->query($sql);
            return (int)$stmt->fetchColumn();
        } catch (Throwable $e) {
            return 0;
        }
    }

}