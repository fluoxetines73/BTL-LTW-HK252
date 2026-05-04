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

        // 1. Nhận tham số Tìm kiếm & Lọc từ URL
        $keyword       = trim((string)($_GET['q'] ?? ''));
        $status        = trim((string)($_GET['status'] ?? 'all'));
        $paymentStatus = trim((string)($_GET['payment_status'] ?? 'all'));
        $sort          = trim((string)($_GET['sort'] ?? 'newest'));

        // 2. Xử lý Xóa hàng loạt (Bulk Delete)
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST' 
            && !empty($_POST['action']) 
            && $_POST['action'] === 'delete_selected') {
            
            $rawSelectedIds = $_POST['selected_ids'] ?? '';
            
            if (is_array($rawSelectedIds)) {
                $selectedIds = array_map('intval', array_map('trim', $rawSelectedIds));
            } elseif (is_string($rawSelectedIds) && $rawSelectedIds !== '') {
                $selectedIds = array_map('intval', array_map('trim', explode(',', $rawSelectedIds)));
            } else {
                $selectedIds = [];
            }
            
            $selectedIds = array_values(array_filter($selectedIds, static fn($id) => $id > 0));

            if (!empty($selectedIds)) {
                if ($orderModel->deleteMultipleOrders($selectedIds)) {
                    $_SESSION['success'] = 'Đã xóa thành công ' . count($selectedIds) . ' Đơn hàng.';
                } else {
                    $_SESSION['error'] = 'Không thể xóa các Đơn hàng đã chọn.';
                }
                $this->redirect('admin/order/index');
                return;
            }
        }

        // 3. Lấy dữ liệu Đơn hàng đã được lọc
        $orders = $orderModel->searchAdminOrders($keyword, $status, $paymentStatus, $sort);
        
        // 4. Gọi View
        $this->adminView('admin/orders/index', 'order', [
            'orders'        => $orders,
            'title'         => 'Quản lý Đơn Hàng',
            'keyword'       => $keyword,
            'status'        => $status,
            'paymentStatus' => $paymentStatus,
            'sort'          => $sort
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