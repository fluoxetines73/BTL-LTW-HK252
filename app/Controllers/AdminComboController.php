<?php
require_once ROOT . '/core/Controller.php';

class AdminComboController extends Controller {
    public function __construct() {
        $this->middlewareAdmin();
    }

    /**
     * Trang danh sách Combo (Có Search, Filter và Bulk Delete)
     */
    public function index() {
        $comboModel = $this->model('Combo');
        $keyword = trim((string)($_GET['q'] ?? ''));
        $status  = trim((string)($_GET['status'] ?? 'all'));
        $sort    = trim((string)($_GET['sort'] ?? 'newest'));

        // Logic Phân trang
        $limit = 10;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        if ($page < 1) $page = 1;
        $offset = ($page - 1) * $limit;

        $totalRows = $comboModel->countAdminCombos($keyword, $status);
        $totalPages = ceil($totalRows / $limit);

        $combos = $comboModel->searchAdminCombos($keyword, $status, $sort, $limit, $offset);

        $this->adminView('admin/combo/index', 'combo', [
            'combos' => $combos,
            'keyword' => $keyword,
            'status' => $status,
            'sort' => $sort,
            'currentPage' => $page,
            'totalPages' => $totalPages
        ]);
    }
    public function create() {
        $this->adminView('admin/combo/create', 'combo', ['title' => 'Thêm Combo Mới']);
    }
        /**
     * Lưu Combo mới (Đã Audit bảo mật + Logic)
     */
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $comboModel = $this->model('Combo');
            $name = trim($_POST['name']);
            $price = (float)$_POST['price'];

            // 1. Chặn trùng tên
            if ($comboModel->isNameExists($name)) {
                $_SESSION['error'] = "Tên Combo này đã tồn tại!";
                $this->redirect('admin/combo/create');
                return;
            }

            // 2. Chặn giá âm
            if ($price < 0) {
                $_SESSION['error'] = "Giá tiền không được nhỏ hơn 0!";
                $this->redirect('admin/combo/create');
                return;
            }

            // 3. Upload ảnh an toàn
            $imageName = $this->handleFileUpload('image') ?: 'default-combo.png';

            $data = [
                'name' => $name,
                'description' => trim($_POST['description'] ?? ''),
                'price' => $price,
                'image' => $imageName,
                'is_active' => $_POST['is_active'] ?? 1
            ];

            if ($comboModel->createCombo($data)) {
                $_SESSION['success'] = "Thêm Combo thành công!";
                $this->redirect('admin/combo/index');
            }
        }
    }
    /**
     * Xử lý xóa Combo
     */
    public function delete($id = null) {
        if ($id) {
            $comboModel = $this->model('Combo');
            
            // Lấy thông tin để xóa file ảnh trong thư mục (nếu không phải ảnh mặc định)
            $combo = $comboModel->getComboById($id);
            if ($combo && $combo['image'] !== 'default-combo.png') {
                $filePath = ROOT . '/public/uploads/combos/' . $combo['image'];
                if (file_exists($filePath)) {
                    unlink($filePath); // Xóa file vật lý để tiết kiệm bộ nhớ
                }
            }
            
            $comboModel->deleteCombo($id);
        }
        $this->redirect('admin/combo/index');
    }

    /**
     * Giao diện chỉnh sửa Combo
     */
    public function edit($id = null) {
        if (!$id) { $this->redirect('admin/combo/index'); return; }

        $comboModel = $this->model('Combo');
        $combo = $comboModel->getComboById($id);

        if (!$combo) { $this->redirect('admin/combo/index'); return; }

        $this->adminView('admin/combo/edit', 'combo', ['combo' => $combo, 'title' => 'Sửa Combo']);
    }

        /**
     * Cập nhật Combo (Dọn dẹp ảnh cũ khi đổi ảnh mới)
     */
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $comboModel = $this->model('Combo');
            $oldCombo = $comboModel->getComboById($id);
            if (!$oldCombo) { $this->redirect('admin/combo/index'); return; }

            $name = trim($_POST['name']);
            if ($comboModel->isNameExists($name, $id)) {
                $_SESSION['error'] = "Tên Combo đã bị trùng!";
                $this->redirect('admin/combo/edit/' . $id);
                return;
            }

            $imageName = $oldCombo['image'];
            $newImage = $this->handleFileUpload('image');
            
            if ($newImage) {
                // Xóa ảnh cũ nếu không phải ảnh mặc định
                if ($oldCombo['image'] !== 'default-combo.png') {
                    $oldPath = ROOT . '/public/uploads/combos/' . $oldCombo['image'];
                    if (file_exists($oldPath)) unlink($oldPath);
                }
                $imageName = $newImage;
            }

            $data = [
                'name' => $name,
                'description' => trim($_POST['description'] ?? ''),
                'price' => (float)$_POST['price'],
                'image' => $imageName,
                'is_active' => (int)$_POST['is_active']
            ];

            if ($comboModel->updateCombo($id, $data)) {
                $_SESSION['success'] = "Cập nhật thành công!";
                $this->redirect('admin/combo/index');
            }
        }
    }
        /**
     * Helper: Xử lý upload an toàn (Chặn file lạ, giới hạn 2MB)
     */
    private function handleFileUpload($fieldName) {
        if (isset($_FILES[$fieldName]) && $_FILES[$fieldName]['error'] === UPLOAD_ERR_OK) {
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            $ext = strtolower(pathinfo($_FILES[$fieldName]['name'], PATHINFO_EXTENSION));
            
            if (in_array($ext, $allowed) && $_FILES[$fieldName]['size'] <= 2 * 1024 * 1024) {
                $uploadDir = ROOT . '/public/uploads/combos/';
                if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

                $newName = time() . '_' . uniqid() . '.' . $ext;
                if (move_uploaded_file($_FILES[$fieldName]['tmp_name'], $uploadDir . $newName)) {
                    return $newName;
                }
            }
        }
        return null;
    }
}