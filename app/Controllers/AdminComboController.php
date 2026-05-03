<?php
require_once ROOT . '/core/Controller.php';

class AdminComboController extends Controller {
    public function __construct() {
        $this->middlewareAdmin();
    }

    public function index() {
        $comboModel = $this->model('Combo');
        $combos = $comboModel->getAllCombos();
        $this->adminView('admin/combo/index', 'combo', ['combos' => $combos, 'title' => 'Quản lý Combo']);
    }

    public function create() {
        $this->adminView('admin/combo/create', 'combo', ['title' => 'Thêm Combo Mới']);
    }
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            // 1. Khởi tạo tên ảnh mặc định nếu user không chọn ảnh
            $imageName = 'default-combo.png'; 

            // 2. Xử lý Upload file (Kiểm tra xem có file gửi lên và không bị lỗi)
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                
                // Khai báo thư mục lưu ảnh
                $uploadDir = ROOT . '/public/uploads/combos/';
                
                // Nếu thư mục chưa tồn tại thì tự động tạo mới
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }

                // Lấy đuôi file (jpg, png, jpeg...)
                $fileExtension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                
                // Đổi tên file bằng hàm time() và uniqid() để đảm bảo 100% không bị trùng lặp đè file cũ
                $imageName = time() . '_' . uniqid() . '.' . $fileExtension;
                
                // Di chuyển file từ thư mục tạm của server vào thư mục dự án
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
            }

            // 3. Gom dữ liệu lưu vào DB
            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'] ?? ''),
                'price' => (float)$_POST['price'],
                'image' => $imageName, // Lưu tên ảnh vừa upload
                'is_active' => $_POST['is_active'] ?? 1
            ];

            $comboModel = $this->model('Combo');
            if ($comboModel->createCombo($data)) {
                $this->redirect('admin/combo/index');
            } else {
                echo "Lỗi khi lưu Combo!";
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
     * Xử lý cập nhật dữ liệu
     */
    public function update($id = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id) {
            $comboModel = $this->model('Combo');
            $oldCombo = $comboModel->getComboById($id);
            
            $imageName = $oldCombo['image']; // Mặc định giữ ảnh cũ

            // Nếu user chọn ảnh mới
            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = ROOT . '/public/uploads/combos/';
                
                // Xóa ảnh cũ nếu không phải ảnh mặc định
                if ($oldCombo['image'] !== 'default-combo.png') {
                    $oldPath = $uploadDir . $oldCombo['image'];
                    if (file_exists($oldPath)) unlink($oldPath);
                }

                $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
                $imageName = time() . '_' . uniqid() . '.' . $fileExt;
                move_uploaded_file($_FILES['image']['tmp_name'], $uploadDir . $imageName);
            }

            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description'] ?? ''),
                'price' => (float)$_POST['price'],
                'image' => $imageName,
                'is_active' => (int)$_POST['is_active']
            ];

            if ($comboModel->updateCombo($id, $data)) {
                $this->redirect('admin/combo/index');
            } else {
                echo "Lỗi cập nhật!";
            }
        }
    }
}