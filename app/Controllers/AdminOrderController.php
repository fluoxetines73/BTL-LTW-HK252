<?php
require_once ROOT . '/core/Controller.php';

class AdminOrderController extends Controller {
    public function __construct() {
        $this->middlewareAdmin();
    }

    // Hiển thị danh sách toàn bộ đơn hàng
    public function index() {
        $orderModel = $this->model('Order');
        
        $keyword = trim($_GET['q'] ?? '');
        $status = $_GET['status'] ?? 'all';
        $sort = $_GET['sort'] ?? 'newest';
        
        // Validate sort parameter
        $validSorts = ['newest', 'oldest', 'price_asc', 'price_desc'];
        if (!in_array($sort, $validSorts)) {
            $sort = 'newest';
        }
        
        // Validate status parameter
        $validStatuses = ['all', 'pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($status, $validStatuses)) {
            $status = 'all';
        }
        
        if ($keyword !== '' || $status !== 'all') {
            $orders = $orderModel->searchOrders($keyword, $status, $sort);
        } else {
            $orders = $orderModel->getAllOrders();
        }
        
        $this->adminView('admin/orders/index', 'order', [
            'orders' => $orders,
            'title' => 'Quản lý Đơn Hàng',
            'keyword' => $keyword,
            'status' => $status,
            'sort' => $sort
        ]);
    }

    // Hiển thị chi tiết 1 đơn hàng
    public function detail($id = null) {
        if (!$id) { $this->redirect('admin/order/index'); return; }

        $orderModel = $this->model('Order');
        $order = $orderModel->getOrderById($id);

        if (!$order) { $this->redirect('admin/order/index'); return; }

        // Lấy thêm danh sách Vé và Combo của đơn hàng đó
        $tickets = $orderModel->getOrderTickets($id);
        $combos = $orderModel->getOrderCombos($id);

        $this->adminView('admin/orders/detail', 'order', [
            'order' => $order,
            'tickets' => $tickets,
            'combos' => $combos,
            'title' => 'Chi Tiết Đơn Hàng #' . $order['booking_code']
        ]);
    }

    // Xử lý cập nhật trạng thái đơn hàng từ Form
    public function updateStatus($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $status = $_POST['status'];
            $orderModel = $this->model('Order');
            
            if ($orderModel->updateStatus($id, $status)) {
                // Set flash message (nếu hệ thống của bạn có làm hàm flash message)
                $_SESSION['success'] = "Cập nhật trạng thái đơn hàng thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật trạng thái thất bại!";
            }
        }
        // Cập nhật xong thì quay lại trang chi tiết đơn đó
        $this->redirect('admin/order/detail/' . $id);
    }
}