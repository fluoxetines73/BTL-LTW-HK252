<?php
require_once ROOT . '/core/Controller.php';

class AdminOrderController extends Controller {
    public function __construct() {
        $this->middlewareAdmin();
    }

    // Hiển thị danh sách đơn hàng (Có Search, Filter, Bulk Delete) - Đã fix route và tối ưu JOIN
    public function index() {
        $orderModel = $this->model('Order');
        $keyword = trim((string)($_GET['q'] ?? ''));
        $status = trim((string)($_GET['status'] ?? 'all'));
        $paymentStatus = trim((string)($_GET['payment_status'] ?? 'all'));
        $sort = trim((string)($_GET['sort'] ?? 'newest'));

        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        // Sử dụng hàm đã tối ưu JOIN từ Model Order
        $totalRows = $orderModel->countAdminOrders($keyword, $status, $paymentStatus);
        $totalPages = ceil($totalRows / $limit);
        $orders = $orderModel->searchAdminOrders($keyword, $status, $paymentStatus, $sort, $limit, $offset);

        // View vẫn nằm trong thư mục plural 'orders'
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

    public function detail($id = null) {
        // Fix redirect 404: về admin/order thay vì admin/order/index
        if (!$id) { $this->redirect('admin/order/index'); return; }
        
        $orderModel = $this->model('Order');
        $order = $orderModel->getOrderById($id);

        if (!$order) { $this->redirect('admin/order/index'); return; }

        $tickets = $orderModel->getOrderTickets($id);
        $combos = $orderModel->getOrderCombos($id);

        $this->adminView('admin/orders/detail', 'order', [
            'order' => $order,
            'tickets' => $tickets,
            'combos' => $combos,
            'title' => 'Chi Tiết Đơn Hàng #' . $order['booking_code']
        ]);
    }

    public function updateStatus($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $status = $_POST['status'];
            $orderModel = $this->model('Order');
            
            if ($orderModel->updateStatus($id, $status)) {
                $_SESSION['success'] = "Cập nhật trạng thái đơn hàng thành công!";
            } else {
                $_SESSION['error'] = "Cập nhật thất bại!";
            }
        }
        // Redirect về trang chi tiết của chính đơn hàng đó
        $this->redirect('admin/order/detail/' . $id);
    }

    public function deleteMultiple() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['ids'])) {
            $ids = explode(',', $_POST['ids']);
            $orderModel = $this->model('Order');
            
            // Sử dụng logic Soft-delete (Cancelled) để bảo toàn dữ liệu tài chính
            if ($orderModel->cancelMultipleOrders($ids)) {
                $_SESSION['success'] = "Đã hủy " . count($ids) . " đơn hàng thành công.";
            } else {
                $_SESSION['error'] = "Lỗi hệ thống khi xử lý!";
            }
        }
        // Fix redirect 404: về admin/order
        $this->redirect('admin/order/index');
    }
}