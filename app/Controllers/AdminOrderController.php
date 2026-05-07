<?php
require_once ROOT . '/core/Controller.php';

class AdminOrderController extends Controller {
    public function __construct() {
        $this->middlewareAdmin();
    }

    // Hiển thị danh sách toàn bộ đơn hàng
    // Hiển thị danh sách toàn bộ đơn hàng (Có Search, Filter và Bulk Delete)
    public function index() {
        $orderModel = $this->model('Order');
        $keyword = trim((string)($_GET['q'] ?? ''));
        $status = trim((string)($_GET['status'] ?? 'all'));
        $paymentStatus = trim((string)($_GET['payment_status'] ?? 'all'));
        $sort = trim((string)($_GET['sort'] ?? 'newest'));

        // Logic Phân trang
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $totalRows = $orderModel->countAdminOrders($keyword, $status, $paymentStatus);
        $totalPages = ceil($totalRows / $limit);

        $orders = $orderModel->searchAdminOrders($keyword, $status, $paymentStatus, $sort, $limit, $offset);

        $this->adminView('admin/orders/index', 'order', [
            'orders' => $orders,
            'keyword' => $keyword,
            'status' => $status,
            'paymentStatus' => $paymentStatus,
            'sort' => $sort,
            'currentPage' => $page,
            'totalPages' => $totalPages
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