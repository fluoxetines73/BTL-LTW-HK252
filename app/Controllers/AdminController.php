<?php
// app/Controllers/AdminController.php

require_once ROOT . '/core/Controller.php';

class AdminController extends Controller {

    /**
     * Trang chủ quản trị (Dashboard)
     * Nhiệm vụ của Thành viên C: Đảm bảo phân quyền admin ở đây
     */
    public function admin_dashboard() {
        // Kiểm tra quyền Admin trước khi cho phép xem trang 
        $this->middlewareAdmin(); 
        
        // Gọi View giao diện quản trị 
        $this->view('layouts/main', [
            'title' => 'Bảng điều khiển Admin',
            'content' => 'admin/dashboard'
        ]);
    }
}