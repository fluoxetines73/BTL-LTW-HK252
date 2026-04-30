<?php
require_once ROOT . '/core/Controller.php';

class AdminOrderController extends Controller {
    public function __construct() {
        $this->middlewareAdmin();
    }

    // Hiển thị danh sách toàn bộ đơn hàng
    public function index() {
        $orderModel = $this->model('Order');
        $orders = $orderModel->getAllOrders();
        
        $this->view('admin/orders/index', [
            'orders' => $orders
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

        $this->view('admin/orders/detail', [
            'order' => $order,
            'tickets' => $tickets,
            'combos' => $combos
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